<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Setting;
use App\Http\Services\ShipinkConfigService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShipinkConfigServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_is_configured_without_card_when_own_carrier_contract(): void
    {
        config([
            'shipink.username' => 'test-user',
            'shipink.password' => 'test-pass',
        ]);

        Setting::saveSingleton([
            'shipink_warehouse_id' => '11111111-1111-1111-1111-111111111111',
            'shipink_carrier_account_id' => '22222222-2222-2222-2222-222222222222',
            'shipink_carrier_provider' => 'own',
        ]);

        $service = app(ShipinkConfigService::class);

        $this->assertTrue($service->isConfigured());
        $this->assertSame([], $service->configurationIssues());
    }

    public function test_is_not_configured_when_shipink_contract_requires_card(): void
    {
        config([
            'shipink.username' => 'test-user',
            'shipink.password' => 'test-pass',
        ]);

        Setting::saveSingleton([
            'shipink_warehouse_id' => '11111111-1111-1111-1111-111111111111',
            'shipink_carrier_account_id' => '22222222-2222-2222-2222-222222222222',
            'shipink_carrier_provider' => 'shipink',
            'shipink_card_id' => null,
        ]);

        $service = app(ShipinkConfigService::class);

        $this->assertFalse($service->isConfigured());
        $this->assertStringContainsString('ödeme kartı', implode(' ', $service->configurationIssues()));
    }

    public function test_is_configured_when_shipink_contract_has_card(): void
    {
        config([
            'shipink.username' => 'test-user',
            'shipink.password' => 'test-pass',
        ]);

        Setting::saveSingleton([
            'shipink_warehouse_id' => '11111111-1111-1111-1111-111111111111',
            'shipink_carrier_account_id' => '22222222-2222-2222-2222-222222222222',
            'shipink_carrier_provider' => 'shipink',
            'shipink_card_id' => '33333333-3333-3333-3333-333333333333',
        ]);

        $service = app(ShipinkConfigService::class);

        $this->assertTrue($service->isConfigured());
    }
}
