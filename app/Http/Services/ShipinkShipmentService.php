<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Support\Str;
use RuntimeException;

class ShipinkShipmentService
{
    public function __construct(
        protected ShipinkApiService $api,
        protected ShipinkConfigService $config,
        protected ShipinkWarehouseService $warehouseService,
    ) {
    }

    public function isConfigured(): bool
    {
        return $this->api->isConfigured();
    }

    /**
     * @return array{success: bool, message: string}
     */
    public function createShipmentForOrder(Order $order): array
    {
        if (! $this->isConfigured()) {
            return ['success' => false, 'message' => 'Shipink yapılandırması eksik.'];
        }

        if (! $order->isDomesticShipment()) {
            return ['success' => false, 'message' => 'Shipink yalnızca yurt içi siparişler için kullanılabilir.'];
        }

        if ($order->hasShipinkShipment()) {
            return ['success' => false, 'message' => 'Bu sipariş için kargo zaten oluşturulmuş.'];
        }

        if ($order->status !== OrderStatus::PREPARING) {
            return ['success' => false, 'message' => 'Kargo yalnızca hazırlanan siparişler için oluşturulabilir.'];
        }

        $order->loadMissing(['user', 'address.city', 'address.county', 'details.product']);

        if ($order->user === null || $order->address === null) {
            return ['success' => false, 'message' => 'Sipariş müşteri veya adres bilgisi eksik.'];
        }

        if ($order->details->isEmpty()) {
            return ['success' => false, 'message' => 'Siparişte ürün bulunamadı.'];
        }

        $addressError = $this->validateShippingAddress($order->address);
        if ($addressError !== null) {
            return ['success' => false, 'message' => $addressError];
        }

        $phoneError = $this->validatePhone($order->user->phone);
        if ($phoneError !== null) {
            return ['success' => false, 'message' => $phoneError];
        }

        try {
            if (! filled($order->shipink_order_id)) {
                $shipinkOrder = $this->api->createOrder($this->buildOrderPayload($order));
                $order->shipink_order_id = (string) ($shipinkOrder['id'] ?? '');

                if (filled($order->shipink_order_id)) {
                    $order->save();
                }
            }

            if (! filled($order->shipink_order_id)) {
                return ['success' => false, 'message' => 'Shipink sipariş ID alınamadı.'];
            }

            $this->warehouseService->ensureReady($this->config->warehouseId());

            $carrierAccount = $this->resolveCarrierAccount();
            $shipment = $this->api->createShipment($this->buildShipmentPayload($order, $carrierAccount));

            $this->applyShipmentData($order, $shipment);
            $order->shipping_carrier = (string) ($carrierAccount['carrier_id'] ?? $this->config->carrierId());
            $order->status = OrderStatus::SHIPPED;
            $order->shipped_at = now();
            $order->shipment_created_at = now();
            $order->shipping_synced_at = now();
            $order->save();

            $carrierLabel = $order->shippingCarrierLabel() ?? 'Kargo';

            return ['success' => true, 'message' => "{$carrierLabel} gönderisi Shipink üzerinden oluşturuldu. Sipariş kargoya verildi olarak işaretlendi."];
        } catch (\Throwable $exception) {
            report($exception);

            return ['success' => false, 'message' => $exception->getMessage()];
        }
    }

    /**
     * @return array{success: bool, message: string}
     */
    public function cancelShipmentForOrder(Order $order): array
    {
        if (! $order->hasShipinkShipment()) {
            return ['success' => false, 'message' => 'Bu sipariş için iptal edilecek kargo kaydı bulunamadı.'];
        }

        if (! $order->canCancelShipinkShipment()) {
            $minutes = (int) config('shipink.shipment_cancel_minutes', 60);

            return ['success' => false, 'message' => "Kargo iptali yalnızca oluşturulduktan sonraki {$minutes} dakika içinde yapılabilir."];
        }

        try {
            $this->api->deleteShipment((string) $order->shipink_shipment_id);

            $order->shipink_shipment_id = null;
            $order->shipping_carrier = null;
            $order->tracking_number = null;
            $order->tracking_url = null;
            $order->shipping_label_url = null;
            $order->shipped_at = null;
            $order->shipment_created_at = null;
            $order->shipping_synced_at = null;
            $order->carrier_picked_up_at = null;
            $order->status = OrderStatus::PREPARING;
            $order->save();

            return ['success' => true, 'message' => 'Kargo gönderisi iptal edildi. Sipariş tekrar hazırlanıyor durumuna alındı.'];
        } catch (\Throwable $exception) {
            report($exception);

            return ['success' => false, 'message' => $exception->getMessage()];
        }
    }

    public function syncOrderShipment(Order $order): bool
    {
        if (! filled($order->shipink_shipment_id)) {
            return false;
        }

        try {
            $shipment = $this->api->getShipment($order->shipink_shipment_id);
            $this->applyShipmentData($order, $shipment);
            $this->applyTrackingStatus($order, $shipment);
            $order->shipping_synced_at = now();
            $order->save();

            return true;
        } catch (\Throwable $exception) {
            report($exception);

            return false;
        }
    }

    /**
     * @param  array<string, mixed>  $shipment
     */
    private function applyShipmentData(Order $order, array $shipment): void
    {
        if (filled($shipment['id'] ?? null)) {
            $order->shipink_shipment_id = (string) $shipment['id'];
        }

        $carrier = is_array($shipment['carrier'] ?? null) ? $shipment['carrier'] : [];
        $tracking = is_array($shipment['tracking'] ?? null) ? $shipment['tracking'] : [];
        $labels = is_array($shipment['document']['labels'] ?? null) ? $shipment['document']['labels'] : [];

        $carrierId = (string) (
            $carrier['carrier_id']
            ?? $carrier['id']
            ?? $shipment['carrier_id']
            ?? ''
        );

        if ($carrierId !== '') {
            $order->shipping_carrier = $carrierId;
        }

        $order->tracking_number = (string) (
            $carrier['shipment_id']
            ?? $tracking['id']
            ?? $order->tracking_number
            ?? ''
        ) ?: null;

        $order->tracking_url = (string) (
            $tracking['url']
            ?? $carrier['tracking_url']
            ?? $order->tracking_url
            ?? ''
        ) ?: null;

        $firstLabel = is_array($labels[0] ?? null) ? $labels[0] : [];
        $order->shipping_label_url = (string) ($firstLabel['pdf'] ?? $order->shipping_label_url ?? '') ?: null;

        if (filled($shipment['shipped_at'] ?? null) && $order->shipped_at === null) {
            $order->shipped_at = $shipment['shipped_at'];
        }

        if (filled($shipment['delivered_at'] ?? null) && $order->delivered_at === null) {
            $order->delivered_at = $shipment['delivered_at'];
        }
    }

    /**
     * @param  array<string, mixed>  $shipment
     */
    private function applyTrackingStatus(Order $order, array $shipment): void
    {
        if ($order->status === OrderStatus::CANCELLED) {
            return;
        }

        $trackingStatus = strtolower((string) ($shipment['tracking']['status'] ?? ''));

        if ($this->isDeliveredStatus($trackingStatus) || filled($shipment['delivered_at'] ?? null)) {
            $order->status = OrderStatus::COMPLETED;
            $order->delivered_at ??= now();
            $order->shipped_at ??= now();
            $order->carrier_picked_up_at ??= now();

            return;
        }

        if ($this->isCarrierPickedUpStatus($trackingStatus)) {
            $order->carrier_picked_up_at ??= now();
            $order->status = OrderStatus::SHIPPED;
            $order->shipped_at ??= now();
        }
    }

    private function isCarrierPickedUpStatus(string $status): bool
    {
        return in_array($status, [
            'picked_up',
            'in_transit',
            'shipped',
            'out_for_delivery',
            'transit',
            'on_the_way',
        ], true);
    }

    private function isDeliveredStatus(string $status): bool
    {
        return in_array($status, ['delivered', 'completed', 'delivery_completed'], true);
    }

    private function isShippedStatus(string $status): bool
    {
        return $this->isCarrierPickedUpStatus($status);
    }

    /**
     * @return array<string, mixed>
     */
    private function buildOrderPayload(Order $order): array
    {
        $address = $order->address;
        $user = $order->user;
        $location = $this->resolveAddressLocation($address);

        $customer = [
            'name' => $user->name,
            'email' => [
                'main' => $user->email,
                'work' => '',
            ],
            'phone' => [
                'main' => $this->formatPhone($user->phone),
                'work' => '',
                'cell' => '',
                'code' => '',
            ],
            'address' => [
                'street' => Str::limit(trim($address->content), 200, ''),
                'city' => $location['city'],
                'state' => $location['state'],
                'zip' => $this->resolvePostalCode($address),
                'country_code' => 'TR',
            ],
        ];

        if ($order->isCorporateInvoice()) {
            $customer['company'] = (string) $order->company_name;
            $customer['tax_id'] = (string) $order->tax_number;
        } else {
            $customer['company'] = '';
            $customer['tax_id'] = '';
        }

        if (filled($location['state_code'])) {
            $customer['address']['state_code'] = $location['state_code'];
        }

        $customer['address']['country'] = 'Turkey';

        $items = $order->details->map(function ($detail) {
            return [
                'name' => Str::limit($detail->product?->title ?? 'Ürün', 100, ''),
                'quantity' => (int) $detail->quantity,
                'category' => 'product',
                'price' => (float) $detail->price,
                'hs_code' => '',
                'origin' => 'TR',
            ];
        })->values()->all();

        $payload = [
            'customer' => $customer,
            'items' => $items,
            'note' => (string) ($order->note ?? ''),
            'language' => 'tr',
            'currency' => 'TRY',
            'price' => (float) $order->total,
            'payment' => [
                'method' => 'credit-card',
                'status' => 'completed',
            ],
        ];

        $salesChannel = $this->config->salesChannel();
        if (filled($salesChannel['id'] ?? null) && filled($salesChannel['name'] ?? null)) {
            $payload['sales_channel'] = [
                'id' => (string) $salesChannel['id'],
                'name' => (string) $salesChannel['name'],
                'order_id' => $order->code,
                'order_number' => $order->code,
            ];
        }

        if ($order->created_at !== null) {
            $payload['placed_at'] = $order->created_at->utc()->format('Y-m-d\TH:i:s\Z');
        }

        return $payload;
    }

    /**
     * @return array{city: string, state: string, state_code: ?string}
     */
    private function resolveAddressLocation(\App\Models\Address $address): array
    {
        $city = (string) ($address->county?->name ?? $address->city_name ?? '');
        $state = (string) ($address->city?->name ?? $address->state ?? '');
        $stateCode = null;

        if ($address->city !== null && filled($address->city->code)) {
            $stateCode = 'TR-'.str_pad((string) $address->city->code, 2, '0', STR_PAD_LEFT);
        }

        return [
            'city' => $city,
            'state' => $state,
            'state_code' => $stateCode,
        ];
    }

    private function validateShippingAddress(\App\Models\Address $address): ?string
    {
        $location = $this->resolveAddressLocation($address);

        if (trim($address->content) === '') {
            return 'Teslimat adresi eksik.';
        }

        if ($location['city'] === '' || $location['state'] === '') {
            return 'Teslimat adresinde il/ilçe bilgisi eksik.';
        }

        return null;
    }

    private function resolvePostalCode(\App\Models\Address $address): string
    {
        $postalCode = trim((string) $address->postal_code);

        return $postalCode !== '' ? $postalCode : '54000';
    }

    private function validatePhone(?string $phone): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $phone) ?? '';

        if (str_starts_with($digits, '90')) {
            $digits = substr($digits, 2);
        } elseif (str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }

        if (strlen($digits) !== 10) {
            return 'Müşteri telefon numarası geçersiz veya eksik.';
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $carrierAccount
     * @return array<string, mixed>
     */
    private function buildShipmentPayload(Order $order, array $carrierAccount): array
    {
        $carrierAccountId = (string) ($carrierAccount['id'] ?? '');
        $carrierServiceId = $this->resolveCarrierServiceId($carrierAccount);

        $payload = [
            'direction' => 'outgoing',
            'order_id' => $order->shipink_order_id,
            'carrier_service_id' => $carrierServiceId,
            'carrier_account_id' => $carrierAccountId,
            'warehouse_id' => $this->config->warehouseId(),
            'packages' => [$this->buildPackagePayload()],
        ];

        if (($carrierAccount['provider'] ?? '') === 'shipink') {
            $cardId = $this->resolveCardId();

            if ($cardId === null) {
                throw new RuntimeException(
                    'Shipink anlaşmalı kargo hesabı için ödeme kartı gerekli. Admin panel → Shipink Ayarları bölümünden kart seçin.'
                );
            }

            $payload['card_id'] = $cardId;
        }

        return $payload;
    }

    /**
     * @return array<string, mixed>
     */
    private function resolveCarrierAccount(): array
    {
        $accounts = $this->api->listCarrierAccounts();
        $configuredId = (string) ($this->config->carrierAccountId() ?? '');
        $preferredCarrier = $this->config->carrierId();

        if ($accounts === []) {
            throw new RuntimeException(
                'Shipink hesabınıza bağlı kargo hesabı yok. Shipink panelinden Aras sözleşmenizi ekleyin veya SHIPINK_BASE_URL değerinin doğru ortamı (dev/prod) gösterdiğini kontrol edin.'
            );
        }

        if ($configuredId !== '') {
            foreach ($accounts as $account) {
                if ((string) ($account['id'] ?? '') === $configuredId) {
                    return $account;
                }
            }
        }

        foreach ($accounts as $account) {
            if (($account['carrier_id'] ?? '') === $preferredCarrier && ($account['status'] ?? 'active') === 'active') {
                return $account;
            }
        }

        foreach ($accounts as $account) {
            if (($account['status'] ?? 'active') === 'active') {
                return $account;
            }
        }

        $available = collect($accounts)
            ->map(fn (array $account) => sprintf(
                '%s (%s)',
                (string) ($account['carrier_id'] ?? 'unknown'),
                (string) ($account['id'] ?? '')
            ))
            ->implode(', ');

        throw new RuntimeException(
            "Seçilen kargo hesabı bu Shipink ortamında bulunamadı. Mevcut hesaplar: {$available}. php artisan shipink:list-ids komutu ile kendi hesabınızdaki ID'leri alın."
        );
    }

    /**
     * @param  array<string, mixed>  $carrierAccount
     */
    private function resolveCarrierServiceId(array $carrierAccount): string
    {
        $configuredServiceId = (string) $this->config->carrierServiceId();
        $services = is_array($carrierAccount['carrier_services'] ?? null)
            ? $carrierAccount['carrier_services']
            : [];

        foreach ($services as $service) {
            if (($service['id'] ?? '') === $configuredServiceId) {
                return $configuredServiceId;
            }
        }

        $firstServiceId = (string) ($services[0]['id'] ?? '');

        if ($firstServiceId !== '') {
            return $firstServiceId;
        }

        return $configuredServiceId;
    }

    private function resolveCardId(): ?string
    {
        $configuredCardId = $this->config->cardId();

        if (is_string($configuredCardId) && $configuredCardId !== '') {
            return $configuredCardId;
        }

        $cards = $this->api->listCards();

        foreach ($cards as $card) {
            if (($card['default'] ?? false) === true && filled($card['id'] ?? null)) {
                return (string) $card['id'];
            }
        }

        $firstCardId = (string) ($cards[0]['id'] ?? '');

        return $firstCardId !== '' ? $firstCardId : null;
    }

    /**
     * @return array<string, int|string>
     */
    private function buildPackagePayload(): array
    {
        $package = $this->config->defaultPackage();

        return [
            'weight' => (int) ($package['weight'] ?? 1),
            'weight_unit' => (string) ($package['weight_unit'] ?? 'kg'),
            'length' => (int) ($package['length'] ?? 20),
            'width' => (int) ($package['width'] ?? 15),
            'height' => (int) ($package['height'] ?? 10),
            'dimension_unit' => (string) ($package['dimension_unit'] ?? 'cm'),
        ];
    }

    private function formatPhone(?string $phone): string
    {
        $digits = preg_replace('/\D+/', '', (string) $phone) ?? '';

        if (str_starts_with($digits, '90')) {
            return $digits;
        }

        if (str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }

        return '90'.$digits;
    }
}
