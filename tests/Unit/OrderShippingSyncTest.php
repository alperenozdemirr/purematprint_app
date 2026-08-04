<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Enums\OrderStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\CreatesDomesticOrders;
use Tests\TestCase;

class OrderShippingSyncTest extends TestCase
{
    use CreatesDomesticOrders;
    use RefreshDatabase;

    public function test_detects_stale_shipping_sync(): void
    {
        config(['shipink.stale_sync_hours' => 6]);

        $order = $this->createDomesticOrder([
            'status' => OrderStatus::SHIPPED,
            'shipink_shipment_id' => 'shipment-stale',
            'shipment_created_at' => now()->subHours(8),
            'shipping_synced_at' => now()->subHours(7),
        ]);

        $this->assertTrue($order->isShippingSyncStale());
    }

    public function test_does_not_flag_completed_orders_as_stale(): void
    {
        config(['shipink.stale_sync_hours' => 6]);

        $order = $this->createDomesticOrder([
            'status' => OrderStatus::COMPLETED,
            'shipink_shipment_id' => 'shipment-done',
            'shipping_synced_at' => now()->subDays(2),
        ]);

        $this->assertFalse($order->isShippingSyncStale());
    }

    public function test_needs_shipink_shipment_for_preparing_domestic_orders(): void
    {
        $order = $this->createDomesticOrder([
            'status' => OrderStatus::PREPARING,
        ]);

        $this->assertTrue($order->needsShipinkShipment());
    }
}
