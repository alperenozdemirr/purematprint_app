<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Models\Setting;

class ShipinkConfigService
{
    public function username(): ?string
    {
        $value = config('shipink.username');

        return filled($value) ? (string) $value : null;
    }

    public function password(): ?string
    {
        $value = config('shipink.password');

        return filled($value) ? (string) $value : null;
    }

    public function baseUrl(): string
    {
        return rtrim((string) config('shipink.base_url', 'https://api.dev.shipink.io'), '/');
    }

    public function warehouseId(): ?string
    {
        return $this->resolve('shipink_warehouse_id', 'shipink.warehouse_id');
    }

    public function carrierAccountId(): ?string
    {
        return $this->resolve('shipink_carrier_account_id', 'shipink.carrier_account_id');
    }

    public function carrierId(): string
    {
        return (string) config('shipink.carrier_id', 'aras');
    }

    public function carrierServiceId(): ?string
    {
        return $this->resolve('shipink_carrier_service_id', 'shipink.carrier_service_id') ?? 'aras_standart';
    }

    public function cardId(): ?string
    {
        return $this->resolve('shipink_card_id', 'shipink.card_id');
    }

    /**
     * @return array{id: string, name: string}
     */
    public function salesChannel(): array
    {
        $salesChannel = config('shipink.sales_channel');

        return [
            'id' => (string) ($salesChannel['id'] ?? 'api'),
            'name' => (string) ($salesChannel['name'] ?? config('app.name', 'PureMatPrint')),
        ];
    }

    /**
     * @return array<string, int|string>
     */
    public function defaultPackage(): array
    {
        $setting = Setting::current();

        return [
            'weight' => (int) ($setting->shipink_default_weight ?: config('shipink.default_package.weight', 1)),
            'weight_unit' => 'kg',
            'length' => (int) ($setting->shipink_default_length ?: config('shipink.default_package.length', 20)),
            'width' => (int) ($setting->shipink_default_width ?: config('shipink.default_package.width', 15)),
            'height' => (int) ($setting->shipink_default_height ?: config('shipink.default_package.height', 10)),
            'dimension_unit' => 'cm',
        ];
    }

    public function hasCredentials(): bool
    {
        return filled($this->username()) && filled($this->password());
    }

    public function isConfigured(): bool
    {
        return $this->hasCredentials()
            && $this->isValidUuid($this->warehouseId())
            && $this->isValidUuid($this->carrierAccountId());
    }

    public function isValidUuid(?string $value): bool
    {
        if (! is_string($value) || $value === '') {
            return false;
        }

        return preg_match(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i',
            $value
        ) === 1;
    }

    private function resolve(string $column, string $configKey): ?string
    {
        $setting = Setting::current();
        $fromDb = $setting->{$column} ?? null;

        if (filled($fromDb)) {
            return (string) $fromDb;
        }

        $fromEnv = config($configKey);

        return filled($fromEnv) ? (string) $fromEnv : null;
    }
}
