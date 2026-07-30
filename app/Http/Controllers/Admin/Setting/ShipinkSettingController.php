<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ShipinkSettingUpdateRequest;
use App\Http\Services\ShipinkApiService;
use App\Http\Services\ShipinkConfigService;
use App\Http\Services\ShipinkWarehouseService;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Throwable;

class ShipinkSettingController extends Controller
{
    public function __construct(
        protected ShipinkApiService $api,
        protected ShipinkConfigService $config,
        protected ShipinkWarehouseService $warehouseService,
    ) {
    }

    public function edit(): View
    {
        $setting = Setting::current();
        $connection = $this->fetchConnectionData();
        $selectedWarehouse = $this->resolveSelectedWarehouse($setting, $connection['warehouses']);
        $warehouseIssues = is_array($selectedWarehouse)
            ? $this->warehouseService->validate($selectedWarehouse)
            : [];

        return view('admin.shipink-settings', [
            'setting' => $setting,
            'connection' => $connection,
            'configured' => $this->config->isConfigured(),
            'hasCredentials' => $this->config->hasCredentials(),
            'selectedWarehouse' => $selectedWarehouse,
            'warehouseIssues' => $warehouseIssues,
        ]);
    }

    public function refresh(): RedirectResponse
    {
        Cache::forget('shipink_access_token');

        return redirect()
            ->route('admin.shipinkSettings')
            ->with('success', 'Shipink verileri yeniden sorgulandı.');
    }

    public function update(ShipinkSettingUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        Setting::saveSingleton([
            'shipink_warehouse_id' => $validated['shipink_warehouse_id'] ?? null,
            'shipink_warehouse_name' => $validated['shipink_warehouse_name'] ?? null,
            'shipink_carrier_account_id' => $validated['shipink_carrier_account_id'] ?? null,
            'shipink_carrier_account_label' => $validated['shipink_carrier_account_label'] ?? null,
            'shipink_carrier_service_id' => $validated['shipink_carrier_service_id'] ?? null,
            'shipink_card_id' => $validated['shipink_card_id'] ?? null,
            'shipink_card_label' => $validated['shipink_card_label'] ?? null,
            'shipink_default_weight' => (int) $validated['shipink_default_weight'],
            'shipink_default_length' => (int) $validated['shipink_default_length'],
            'shipink_default_width' => (int) $validated['shipink_default_width'],
            'shipink_default_height' => (int) $validated['shipink_default_height'],
        ]);

        $warehouseId = $validated['shipink_warehouse_id'] ?? null;

        if (filled($warehouseId) && filled($validated['shipink_sender_person_name'] ?? null)) {
            $existing = $this->api->getWarehouse((string) $warehouseId);
            $this->api->updateWarehouse(
                (string) $warehouseId,
                $this->warehouseService->buildUpdatePayload([
                    'name' => $validated['shipink_sender_name'] ?? ($existing['name'] ?? 'Depo'),
                    'person_name' => $validated['shipink_sender_person_name'] ?? '',
                    'company_name' => $validated['shipink_sender_company_name'] ?? '',
                    'tax_id' => $validated['shipink_sender_tax_id'] ?? '',
                    'tax_office' => $validated['shipink_sender_tax_office'] ?? '',
                    'phone' => $validated['shipink_sender_phone'] ?? '',
                    'email' => $validated['shipink_sender_email'] ?? '',
                    'street' => $validated['shipink_sender_street'] ?? '',
                    'city' => $validated['shipink_sender_city'] ?? '',
                    'state' => $validated['shipink_sender_state'] ?? '',
                    'zip' => $validated['shipink_sender_zip'] ?? '',
                ], $existing)
            );
        }

        return redirect()
            ->route('admin.shipinkSettings')
            ->with('success', 'Shipink ayarları kaydedildi.');
    }

    /**
     * @return array{
     *     ok: bool,
     *     error: ?string,
     *     base_url: string,
     *     warehouses: list<array<string, mixed>>,
     *     carrier_accounts: list<array<string, mixed>>,
     *     cards: list<array<string, mixed>>
     * }
     */
    private function fetchConnectionData(): array
    {
        $payload = [
            'ok' => false,
            'error' => null,
            'base_url' => $this->config->baseUrl(),
            'warehouses' => [],
            'carrier_accounts' => [],
            'cards' => [],
        ];

        if (! $this->config->hasCredentials()) {
            $payload['error'] = 'Shipink API kullanıcı adı ve şifresi .env dosyasında tanımlı olmalı.';

            return $payload;
        }

        try {
            $payload['warehouses'] = $this->api->listWarehouses();
            $payload['carrier_accounts'] = $this->api->listCarrierAccounts();
            $payload['cards'] = $this->api->listCards();
            $payload['ok'] = true;
        } catch (Throwable $exception) {
            $payload['error'] = $exception->getMessage();
        }

        return $payload;
    }

    /**
     * @param  list<array<string, mixed>>  $warehouses
     * @return array<string, mixed>|null
     */
    private function resolveSelectedWarehouse(Setting $setting, array $warehouses): ?array
    {
        $selectedId = $setting->shipink_warehouse_id ?: config('shipink.warehouse_id');

        if (! filled($selectedId)) {
            return null;
        }

        foreach ($warehouses as $warehouse) {
            if (($warehouse['id'] ?? '') === $selectedId) {
                return $warehouse;
            }
        }

        if (! $this->config->hasCredentials()) {
            return null;
        }

        try {
            return $this->api->getWarehouse((string) $selectedId);
        } catch (Throwable) {
            return null;
        }
    }
}
