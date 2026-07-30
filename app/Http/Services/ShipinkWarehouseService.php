<?php

declare(strict_types=1);

namespace App\Http\Services;

use RuntimeException;

class ShipinkWarehouseService
{
    public function __construct(protected ShipinkApiService $api)
    {
    }

    /**
     * @return list<string>
     */
    public function validate(array $warehouse): array
    {
        $issues = [];
        $address = is_array($warehouse['address'] ?? null) ? $warehouse['address'] : [];
        $company = is_array($warehouse['company'] ?? null) ? $warehouse['company'] : [];
        $phone = is_array($warehouse['phone'] ?? null) ? $warehouse['phone'] : [];

        if (trim((string) ($warehouse['person_name'] ?? '')) === '') {
            $issues[] = 'yetkili adı';
        }

        $email = is_array($warehouse['email'] ?? null)
            ? (string) ($warehouse['email']['main'] ?? '')
            : (string) ($warehouse['email'] ?? '');

        if (trim((string) ($company['name'] ?? '')) === '') {
            $issues[] = 'firma adı';
        }

        if (trim((string) ($company['tax_id'] ?? '')) === '') {
            $issues[] = 'vergi numarası';
        }

        if (! $this->isValidShipinkPhone($phone)) {
            $issues[] = 'telefon (905XXXXXXXXX formatında olmalı)';
        }

        if (trim($email) === '') {
            $issues[] = 'e-posta';
        }

        if (trim((string) ($address['street'] ?? '')) === '') {
            $issues[] = 'adres satırı';
        }

        if (trim((string) ($address['city'] ?? '')) === '') {
            $issues[] = 'ilçe';
        }

        if (trim((string) ($address['state'] ?? '')) === '') {
            $issues[] = 'il';
        }

        if (trim((string) ($address['zip'] ?? '')) === '') {
            $issues[] = 'posta kodu';
        }

        if (trim((string) ($address['state_code'] ?? '')) === '') {
            $issues[] = 'il kodu (state_code)';
        }

        return $issues;
    }

    public function ensureReady(?string $warehouseId): void
    {
        if (! filled($warehouseId)) {
            throw new RuntimeException('Gönderim deposu seçilmemiş. Shipink Ayarları sayfasından depo seçin.');
        }

        $warehouse = $this->api->getWarehouse($warehouseId);
        $updatePayload = $this->buildNormalizationPayload($warehouse);

        if ($updatePayload !== []) {
            $this->api->updateWarehouse($warehouseId, $updatePayload);
            $warehouse = $this->api->getWarehouse($warehouseId);
        }

        $issues = $this->validate($warehouse);

        if ($issues !== []) {
            throw new RuntimeException(
                'Gönderici (depo) adresi geçersiz veya eksik: '.implode(', ', $issues).'. Admin panel → Shipink Ayarları bölümünden gönderici bilgilerini güncelleyin.'
            );
        }
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    public function buildUpdatePayload(array $input, ?array $existing = null): array
    {
        $existingAddress = is_array($existing['address'] ?? null) ? $existing['address'] : [];
        $normalizedPhone = $this->normalizePhoneString((string) ($input['phone'] ?? ''));

        $payload = [
            'name' => (string) ($input['name'] ?? $existing['name'] ?? 'Depo'),
            'person_name' => (string) ($input['person_name'] ?? ''),
            'company' => [
                'name' => (string) ($input['company_name'] ?? ''),
                'tax_id' => (string) ($input['tax_id'] ?? ''),
                'tax_office' => (string) ($input['tax_office'] ?? ''),
            ],
            'phone' => [
                'main' => $normalizedPhone,
                'work' => '',
                'cell' => '',
                'code' => '',
            ],
            'email' => [
                'main' => (string) ($input['email'] ?? ''),
                'work' => '',
            ],
            'address' => [
                'street' => (string) ($input['street'] ?? ''),
                'city' => (string) ($input['city'] ?? ''),
                'state' => (string) ($input['state'] ?? ''),
                'zip' => (string) ($input['zip'] ?? ''),
                'country_code' => 'TR',
            ],
        ];

        if (filled($existingAddress['city_code'] ?? null)) {
            $payload['address']['city_code'] = (string) $existingAddress['city_code'];
        }

        if (filled($existingAddress['state_code'] ?? null)) {
            $payload['address']['state_code'] = (string) $existingAddress['state_code'];
        } elseif (filled($input['state_code'] ?? null)) {
            $payload['address']['state_code'] = (string) $input['state_code'];
        }

        return $payload;
    }

    /**
     * @param  array<string, mixed>  $warehouse
     * @return array<string, mixed>
     */
    private function buildNormalizationPayload(array $warehouse): array
    {
        $phone = is_array($warehouse['phone'] ?? null) ? $warehouse['phone'] : [];
        $normalizedPhone = $this->normalizePhoneFromArray($phone);

        if ($normalizedPhone === null) {
            return [];
        }

        $currentMain = preg_replace('/\D+/', '', (string) ($phone['main'] ?? '')) ?? '';

        if ($currentMain === $normalizedPhone) {
            return [];
        }

        return [
            'phone' => [
                'main' => $normalizedPhone,
                'work' => '',
                'cell' => '',
                'code' => '',
            ],
        ];
    }

    /**
     * @param  array<string, mixed>  $phone
     */
    private function isValidShipinkPhone(array $phone): bool
    {
        $normalized = $this->normalizePhoneFromArray($phone);

        return is_string($normalized) && strlen($normalized) === 12 && str_starts_with($normalized, '90');
    }

    /**
     * @param  array<string, mixed>  $phone
     */
    private function normalizePhoneFromArray(array $phone): ?string
    {
        $main = preg_replace('/\D+/', '', (string) ($phone['main'] ?? '')) ?? '';
        $code = preg_replace('/\D+/', '', (string) ($phone['code'] ?? '')) ?? '';

        if ($main === '') {
            return null;
        }

        if (str_starts_with($main, '90') && strlen($main) === 12) {
            return $main;
        }

        if (strlen($main) === 10) {
            return '90'.$main;
        }

        if ($code === '90' && strlen($main) === 10) {
            return '90'.$main;
        }

        if (str_starts_with($main, '0') && strlen($main) === 11) {
            return '90'.substr($main, 1);
        }

        return strlen($main) >= 10 ? $this->normalizePhoneString($main) : null;
    }

    private function normalizePhoneString(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';

        if (str_starts_with($digits, '90') && strlen($digits) === 12) {
            return $digits;
        }

        if (str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }

        if (strlen($digits) === 10) {
            return '90'.$digits;
        }

        return $digits;
    }
}
