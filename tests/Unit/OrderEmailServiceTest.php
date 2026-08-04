<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Enums\OrderStatus;
use App\Http\Services\OrderEmailService;
use App\Jobs\SendOrderDeliveredEmailJob;
use App\Jobs\SendOrderShippedEmailJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Tests\Support\CreatesDomesticOrders;
use Tests\TestCase;

class OrderEmailServiceTest extends TestCase
{
    use CreatesDomesticOrders;
    use RefreshDatabase;

    public function test_domestic_shipped_email_waits_for_carrier_pickup(): void
    {
        Bus::fake();

        $order = $this->createDomesticOrder([
            'status' => OrderStatus::SHIPPED,
            'shipink_shipment_id' => 'shipment-1',
        ]);

        $service = app(OrderEmailService::class);

        $this->assertFalse($service->sendShippedIfNeeded($order));
        Bus::assertNotDispatched(SendOrderShippedEmailJob::class);
    }

    public function test_domestic_shipped_email_dispatches_after_carrier_pickup(): void
    {
        Bus::fake();

        $order = $this->createDomesticOrder([
            'status' => OrderStatus::SHIPPED,
            'shipink_shipment_id' => 'shipment-2',
            'carrier_picked_up_at' => now(),
        ]);

        $service = app(OrderEmailService::class);

        $this->assertTrue($service->sendShippedIfNeeded($order));
        Bus::assertDispatched(SendOrderShippedEmailJob::class);
    }

    public function test_delivered_email_not_sent_for_returned_like_shipped_state(): void
    {
        Bus::fake();

        $order = $this->createDomesticOrder([
            'status' => OrderStatus::SHIPPED,
            'shipink_shipment_id' => 'shipment-3',
            'carrier_picked_up_at' => now(),
        ]);

        $service = app(OrderEmailService::class);

        $this->assertFalse($service->sendDeliveredIfNeeded($order));
        Bus::assertNotDispatched(SendOrderDeliveredEmailJob::class);
    }

    public function test_delivered_email_sent_only_when_completed(): void
    {
        Bus::fake();

        $order = $this->createDomesticOrder([
            'status' => OrderStatus::COMPLETED,
            'shipink_shipment_id' => 'shipment-4',
            'carrier_picked_up_at' => now()->subDay(),
            'delivered_at' => now(),
        ]);

        $service = app(OrderEmailService::class);

        $this->assertTrue($service->sendDeliveredIfNeeded($order));
        Bus::assertDispatched(SendOrderDeliveredEmailJob::class);
    }
}
