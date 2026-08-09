<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Support\Str;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripePaymentService
{
    public function __construct(protected OrderPricingService $pricingService)
    {
    }

    public function isConfigured(): bool
    {
        return filled(config('stripe.secret'))
            && filled(config('stripe.success_url'))
            && filled(config('stripe.cancel_url'));
    }

    /**
     * @return array{success: bool, checkoutUrl: ?string, sessionId: ?string, error: ?string}
     */
    public function createCheckoutSession(array $draft, User $user, iterable $cartItems): array
    {
        try {
            Stripe::setApiKey((string) config('stripe.secret'));

            $session = Session::create([
                'mode' => 'payment',
                'customer_email' => $user->email,
                'client_reference_id' => $draft['draft_id'],
                'line_items' => $this->buildLineItems($cartItems, $draft['summary']),
                'success_url' => (string) config('stripe.success_url'),
                'cancel_url' => (string) config('stripe.cancel_url'),
                'metadata' => [
                    'draft_id' => $draft['draft_id'],
                    'user_id' => (string) $user->id,
                ],
            ]);

            if (empty($session->url) || empty($session->id)) {
                return [
                    'success' => false,
                    'checkoutUrl' => null,
                    'sessionId' => null,
                    'error' => 'Stripe ödeme oturumu oluşturulamadı.',
                ];
            }

            return [
                'success' => true,
                'checkoutUrl' => $session->url,
                'sessionId' => $session->id,
                'error' => null,
            ];
        } catch (\Throwable $exception) {
            report($exception);

            return [
                'success' => false,
                'checkoutUrl' => null,
                'sessionId' => null,
                'error' => 'Stripe ödeme sayfası oluşturulamadı.',
            ];
        }
    }

    public function retrieveSession(string $sessionId): Session
    {
        Stripe::setApiKey((string) config('stripe.secret'));

        return Session::retrieve($sessionId);
    }

    public function isPaymentSuccessful(Session $session): bool
    {
        return $session->payment_status === 'paid';
    }

    public function paidAmount(Session $session): float
    {
        if ($session->amount_total === null) {
            return 0.0;
        }

        return round(((int) $session->amount_total) / 100, 2);
    }

    public function paymentReference(Session $session): ?string
    {
        if (is_string($session->payment_intent)) {
            return $session->payment_intent;
        }

        return $session->id;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function buildLineItems(iterable $cartItems, array $summary): array
    {
        $currency = strtolower((string) config('stripe.currency', 'try'));
        $subtotal = (float) $summary['subtotal'];
        $discountFactor = $subtotal > 0 && ($summary['discountApplied'] ?? false)
            ? max(0, ($subtotal - (float) $summary['discountAmount']) / $subtotal)
            : 1.0;

        $lineItems = [];

        foreach ($cartItems as $item) {
            $unitAmount = (int) round(
                $this->pricingService->unitPriceForCartItem($item) * $discountFactor * 100
            );

            $lineItems[] = [
                'price_data' => [
                    'currency' => $currency,
                    'product_data' => [
                        'name' => Str::limit($item->product->title, 100, ''),
                    ],
                    'unit_amount' => max($unitAmount, 0),
                ],
                'quantity' => (int) $item->quantity,
            ];
        }

        if (! ($summary['shippingFree'] ?? true) && (float) ($summary['shippingCost'] ?? 0) > 0) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => $currency,
                    'product_data' => [
                        'name' => 'Shipping',
                    ],
                    'unit_amount' => (int) round((float) $summary['shippingCost'] * 100),
                ],
                'quantity' => 1,
            ];
        }

        return $lineItems;
    }
}
