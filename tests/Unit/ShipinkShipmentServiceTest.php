<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Enums\OrderStatus;
use App\Http\Services\OrderEmailService;
use App\Http\Services\ShipinkApiService;
use App\Http\Services\OrderPackageCalculator;
use App\Http\Services\ShipinkConfigService;
use App\Http\Services\ShipinkShipmentService;
use App\Http\Services\ShipinkWarehouseService;
use App\Jobs\SendOrderShippedEmailJob;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Mockery;
use Tests\Support\CreatesDomesticOrders;
use Tests\TestCase;

class ShipinkShipmentServiceTest extends TestCase
{
    use CreatesDomesticOrders;
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    public function test_cancel_clears_shipink_and_email_state_for_recreate(): void
    {
        $order = $this->createDomesticOrder([
            'status' => OrderStatus::SHIPPED,
            'shipink_order_id' => 'order-123',
            'shipink_shipment_id' => 'shipment-123',
            'shipment_created_at' => now(),
            'shipped_email_shipment_id' => 'shipment-123',
        ]);

        $api = Mockery::mock(ShipinkApiService::class);
        $api->shouldReceive('deleteShipment')->once()->with('shipment-123');

        $service = new ShipinkShipmentService(
            $api,
            app(ShipinkConfigService::class),
            Mockery::mock(ShipinkWarehouseService::class),
            app(OrderPackageCalculator::class),
        );

        $result = $service->cancelShipmentForOrder($order->fresh());

        $this->assertTrue($result['success']);

        $order->refresh();
        $this->assertNull($order->shipink_order_id);
        $this->assertNull($order->shipink_shipment_id);
        $this->assertNull($order->shipped_email_shipment_id);
        $this->assertSame(OrderStatus::PREPARING, $order->status);
    }

    public function test_sync_marks_delivered_without_extra_email_types(): void
    {
        Bus::fake();

        $order = $this->createDomesticOrder([
            'status' => OrderStatus::SHIPPED,
            'shipink_shipment_id' => 'shipment-456',
            'carrier_picked_up_at' => now()->subHour(),
            'shipped_email_shipment_id' => 'shipment-456',
        ]);

        $api = Mockery::mock(ShipinkApiService::class);
        $api->shouldReceive('getShipment')->once()->with('shipment-456')->andReturn([
            'id' => 'shipment-456',
            'tracking' => ['status' => 'delivered'],
            'delivered_at' => now()->toIso8601String(),
        ]);

        $service = new ShipinkShipmentService(
            $api,
            app(ShipinkConfigService::class),
            Mockery::mock(ShipinkWarehouseService::class),
            app(OrderPackageCalculator::class),
        );

        $this->assertTrue($service->syncOrderShipment($order->fresh()));

        $order->refresh();
        $this->assertSame(OrderStatus::COMPLETED, $order->status);
        $this->assertNotNull($order->delivered_at);

        app(OrderEmailService::class)->sendShippedIfNeeded($order);
        Bus::assertNotDispatched(SendOrderShippedEmailJob::class);
    }

    public function test_sync_handles_returned_status_without_completing_order(): void
    {
        $order = $this->createDomesticOrder([
            'status' => OrderStatus::SHIPPED,
            'shipink_shipment_id' => 'shipment-789',
            'carrier_picked_up_at' => now()->subDay(),
        ]);

        $api = Mockery::mock(ShipinkApiService::class);
        $api->shouldReceive('getShipment')->once()->andReturn([
            'id' => 'shipment-789',
            'tracking' => ['status' => 'returned'],
        ]);

        $service = new ShipinkShipmentService(
            $api,
            app(ShipinkConfigService::class),
            Mockery::mock(ShipinkWarehouseService::class),
            app(OrderPackageCalculator::class),
        );

        $this->assertTrue($service->syncOrderShipment($order->fresh()));

        $order->refresh();
        $this->assertSame(OrderStatus::SHIPPED, $order->status);
        $this->assertNull($order->delivered_at);
        $this->assertFalse(app(OrderEmailService::class)->sendDeliveredIfNeeded($order));
    }

    public function test_sync_carrier_cancelled_resets_local_shipment_state(): void
    {
        $order = $this->createDomesticOrder([
            'status' => OrderStatus::SHIPPED,
            'shipink_order_id' => 'order-999',
            'shipink_shipment_id' => 'shipment-999',
            'carrier_picked_up_at' => now(),
        ]);

        $api = Mockery::mock(ShipinkApiService::class);
        $api->shouldReceive('getShipment')->once()->andReturn([
            'id' => 'shipment-999',
            'tracking' => ['status' => 'cancelled'],
        ]);

        $service = new ShipinkShipmentService(
            $api,
            app(ShipinkConfigService::class),
            Mockery::mock(ShipinkWarehouseService::class),
            app(OrderPackageCalculator::class),
        );

        $this->assertTrue($service->syncOrderShipment($order->fresh()));

        $order->refresh();
        $this->assertSame(OrderStatus::PREPARING, $order->status);
        $this->assertNull($order->shipink_shipment_id);
        $this->assertNull($order->shipink_order_id);
    }

    public function test_create_rejects_when_shipment_already_exists(): void
    {
        config([
            'shipink.username' => 'test-user',
            'shipink.password' => 'test-pass',
        ]);

        \App\Models\Setting::saveSingleton([
            'shipink_warehouse_id' => '11111111-1111-1111-1111-111111111111',
            'shipink_carrier_account_id' => '22222222-2222-2222-2222-222222222222',
            'shipink_carrier_provider' => 'own',
        ]);

        $order = $this->createDomesticOrder([
            'shipink_shipment_id' => 'existing-shipment',
            'status' => OrderStatus::PREPARING,
        ]);

        $api = Mockery::mock(ShipinkApiService::class);
        $api->shouldNotReceive('createOrder');
        $api->shouldNotReceive('createShipment');

        $service = new ShipinkShipmentService(
            $api,
            app(ShipinkConfigService::class),
            Mockery::mock(ShipinkWarehouseService::class),
            app(OrderPackageCalculator::class),
        );

        $result = $service->createShipmentForOrder($order->fresh());

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('zaten oluşturulmuş', $result['message']);
    }

    public function test_create_applies_admin_package_override(): void
    {
        config([
            'shipink.username' => 'test-user',
            'shipink.password' => 'test-pass',
        ]);

        \App\Models\Setting::saveSingleton([
            'shipink_warehouse_id' => '11111111-1111-1111-1111-111111111111',
            'shipink_carrier_account_id' => '22222222-2222-2222-2222-222222222222',
            'shipink_carrier_provider' => 'own',
        ]);

        $order = $this->createDomesticOrder([
            'status' => OrderStatus::PREPARING,
        ]);

        $carrierAccount = [
            'id' => '22222222-2222-2222-2222-222222222222',
            'carrier_id' => 'aras',
            'provider' => 'own',
            'status' => 'active',
            'carrier_services' => [
                ['id' => 'aras_standart'],
            ],
        ];

        $api = Mockery::mock(ShipinkApiService::class);
        $api->shouldReceive('listCarrierAccounts')->once()->andReturn([$carrierAccount]);
        $api->shouldReceive('createOrder')->once()->andReturn(['id' => 'order-new']);
        $api->shouldReceive('createShipment')
            ->once()
            ->with(Mockery::on(function (array $payload) {
                $package = $payload['packages'][0] ?? [];

                return ($package['weight'] ?? null) === 5
                    && ($package['length'] ?? null) === 30
                    && ($package['width'] ?? null) === 25
                    && ($package['height'] ?? null) === 20;
            }))
            ->andReturn([
                'id' => 'shipment-new',
                'carrier' => ['carrier_id' => 'aras'],
            ]);

        $warehouse = Mockery::mock(ShipinkWarehouseService::class);
        $warehouse->shouldReceive('ensureReady')->once();

        $service = new ShipinkShipmentService(
            $api,
            app(ShipinkConfigService::class),
            $warehouse,
            app(OrderPackageCalculator::class),
        );

        $result = $service->createShipmentForOrder($order->fresh(), [
            'weight' => 5,
            'length' => 30,
            'width' => 25,
            'height' => 20,
        ]);

        $this->assertTrue($result['success']);
    }
}
