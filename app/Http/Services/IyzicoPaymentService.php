<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Enums\InvoiceType;
use App\Models\Address;
use App\Models\User;
use Illuminate\Support\Str;
use Iyzipay\Model\Address as IyzicoAddress;
use Iyzipay\Model\BasketItem;
use Iyzipay\Model\BasketItemType;
use Iyzipay\Model\Buyer;
use Iyzipay\Model\CheckoutForm;
use Iyzipay\Model\CheckoutFormInitialize;
use Iyzipay\Options;
use Iyzipay\Request\CreateCheckoutFormInitializeRequest;
use Iyzipay\Request\RetrieveCheckoutFormRequest;

class IyzicoPaymentService
{
    public function isConfigured(): bool
    {
        return filled(config('iyzico.api_key'))
            && filled(config('iyzico.secret_key'))
            && filled(config('iyzico.callback_url'));
    }

    public function options(): Options
    {
        $options = new Options();
        $options->setApiKey((string) config('iyzico.api_key'));
        $options->setSecretKey((string) config('iyzico.secret_key'));
        $options->setBaseUrl((string) config('iyzico.base_url'));

        return $options;
    }

    /**
     * @return array{success: bool, paymentPageUrl: ?string, token: ?string, error: ?string}
     */
    public function initializeCheckoutFromDraft(
        array $draft,
        User $user,
        Address $address,
        iterable $cartItems,
        string $clientIp,
    ): array {
        $summary = $draft['summary'];

        $request = new CreateCheckoutFormInitializeRequest();
        $request->setLocale('tr');
        $request->setConversationId($draft['draft_id']);
        $request->setPrice($this->formatAmount($this->basketTotal($cartItems, $summary)));
        $request->setPaidPrice($this->formatAmount((float) $summary['total']));
        $request->setCurrency('TRY');
        $request->setBasketId($draft['draft_id']);
        $request->setPaymentGroup('PRODUCT');
        $request->setCallbackUrl((string) config('iyzico.callback_url'));
        $request->setEnabledInstallments([1, 2, 3, 6, 9]);
        $request->setBuyer($this->buildBuyer($draft['invoice'], $user, $address, $clientIp));
        $request->setShippingAddress($this->buildAddress($user, $address));
        $request->setBillingAddress($this->buildAddress($user, $address));
        $request->setBasketItems($this->buildBasketItems($cartItems, $summary));

        $initialize = CheckoutFormInitialize::create($request, $this->options());

        if ($initialize->getStatus() !== 'success') {
            return [
                'success' => false,
                'paymentPageUrl' => null,
                'token' => null,
                'error' => $initialize->getErrorMessage() ?: 'iyzico ödeme başlatılamadı.',
            ];
        }

        return [
            'success' => true,
            'paymentPageUrl' => $initialize->getPaymentPageUrl(),
            'token' => $initialize->getToken(),
            'error' => null,
        ];
    }

    public function retrieveCheckout(string $token): CheckoutForm
    {
        $request = new RetrieveCheckoutFormRequest();
        $request->setLocale('tr');
        $request->setConversationId(Str::uuid()->toString());
        $request->setToken($token);

        return CheckoutForm::retrieve($request, $this->options());
    }

    public function isPaymentSuccessful(CheckoutForm $checkoutForm): bool
    {
        return $checkoutForm->getStatus() === 'success'
            && $checkoutForm->getPaymentStatus() === 'SUCCESS';
    }

    /**
     * @param  array<string, mixed>  $invoice
     */
    private function buildBuyer(array $invoice, User $user, Address $address, string $clientIp): Buyer
    {
        [$name, $surname] = $this->splitName($user->name);

        $buyer = new Buyer();
        $buyer->setId((string) $user->id);
        $buyer->setName($name);
        $buyer->setSurname($surname);
        $buyer->setEmail($user->email);
        $buyer->setGsmNumber($this->formatPhone($user->phone));
        $buyer->setIdentityNumber($this->identityNumber($invoice));
        $buyer->setRegistrationAddress($this->addressLine($address));
        $buyer->setCity($this->cityName($address));
        $buyer->setCountry($this->countryName($address));
        $buyer->setZipCode($this->zipCode($address));
        $buyer->setIp($clientIp);
        $buyer->setRegistrationDate($user->created_at?->format('Y-m-d H:i:s') ?? now()->format('Y-m-d H:i:s'));
        $buyer->setLastLoginDate(now()->format('Y-m-d H:i:s'));

        return $buyer;
    }

    private function buildAddress(User $user, Address $address): IyzicoAddress
    {
        $iyzicoAddress = new IyzicoAddress();
        $iyzicoAddress->setContactName($user->name);
        $iyzicoAddress->setAddress($this->addressLine($address));
        $iyzicoAddress->setCity($this->cityName($address));
        $iyzicoAddress->setCountry($this->countryName($address));
        $iyzicoAddress->setZipCode($this->zipCode($address));

        return $iyzicoAddress;
    }

    /**
     * @return list<BasketItem>
     */
    private function buildBasketItems(iterable $cartItems, array $summary): array
    {
        $items = [];

        foreach ($cartItems as $item) {
            $basketItem = new BasketItem();
            $basketItem->setId((string) $item->product_id);
            $basketItem->setName(Str::limit($item->product->title, 100, ''));
            $basketItem->setCategory1('Ürün');
            $basketItem->setItemType(BasketItemType::PHYSICAL);
            $basketItem->setPrice($this->formatAmount((float) $item->product->price * (int) $item->quantity));
            $items[] = $basketItem;
        }

        if (! $summary['shippingFree'] && (float) $summary['shippingCost'] > 0) {
            $shippingItem = new BasketItem();
            $shippingItem->setId('shipping');
            $shippingItem->setName('Kargo');
            $shippingItem->setCategory1('Kargo');
            $shippingItem->setItemType(BasketItemType::PHYSICAL);
            $shippingItem->setPrice($this->formatAmount((float) $summary['shippingCost']));
            $items[] = $shippingItem;
        }

        return $items;
    }

    private function basketTotal(iterable $cartItems, array $summary): float
    {
        $total = 0.0;

        foreach ($cartItems as $item) {
            $total += (float) $item->product->price * (int) $item->quantity;
        }

        if (! $summary['shippingFree']) {
            $total += (float) $summary['shippingCost'];
        }

        return round($total, 2);
    }

    /**
     * @param  array<string, mixed>  $invoice
     */
    private function identityNumber(array $invoice): string
    {
        $invoiceType = $invoice['invoice_type'] ?? null;

        if ($invoiceType instanceof InvoiceType) {
            $invoiceType = $invoiceType->value;
        }

        if ($invoiceType === InvoiceType::INDIVIDUAL->value && filled($invoice['tc_no'] ?? null)) {
            return (string) $invoice['tc_no'];
        }

        if ($invoiceType === InvoiceType::CORPORATE->value && filled($invoice['tax_number'] ?? null)) {
            return Str::limit((string) $invoice['tax_number'], 11, '');
        }

        return '11111111111';
    }

    private function addressLine(Address $address): string
    {
        return Str::limit(trim($address->content), 200, '');
    }

    private function cityName(Address $address): string
    {
        if ($address->isInternational()) {
            return Str::limit((string) ($address->city_name ?: $address->state ?: 'Istanbul'), 50, '');
        }

        return Str::limit((string) ($address->city?->name ?: 'Istanbul'), 50, '');
    }

    private function countryName(Address $address): string
    {
        if ($address->isInternational()) {
            return Str::limit((string) ($address->country ?: 'Turkey'), 50, '');
        }

        return 'Turkey';
    }

    private function zipCode(Address $address): string
    {
        if ($address->isInternational() && filled($address->postal_code)) {
            return Str::limit((string) $address->postal_code, 20, '');
        }

        return '34000';
    }

    /**
     * @return array{0: string, 1: string}
     */
    private function splitName(string $fullName): array
    {
        $fullName = trim($fullName);

        if ($fullName === '') {
            return ['Musteri', 'Kullanici'];
        }

        $parts = preg_split('/\s+/', $fullName) ?: [];

        if (count($parts) === 1) {
            return [Str::limit($parts[0], 50, ''), 'Kullanici'];
        }

        $surname = array_pop($parts);
        $name = implode(' ', $parts);

        return [Str::limit($name, 50, ''), Str::limit((string) $surname, 50, '')];
    }

    private function formatPhone(?string $phone): string
    {
        $digits = preg_replace('/\D+/', '', (string) $phone) ?? '';

        if (str_starts_with($digits, '90')) {
            $digits = substr($digits, 2);
        }

        if (str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }

        if (strlen($digits) === 10) {
            return '+90'.$digits;
        }

        return '+905555555555';
    }

    private function formatAmount(float $amount): string
    {
        return number_format($amount, 2, '.', '');
    }
}
