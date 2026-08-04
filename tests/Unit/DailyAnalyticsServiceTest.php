<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Enums\PaymentProvider;
use App\Enums\PaymentStatus;
use App\Http\Services\DailyAnalyticsService;
use App\Models\DailyAnalytic;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\Support\CreatesDomesticOrders;
use Tests\TestCase;

class DailyAnalyticsServiceTest extends TestCase
{
    use CreatesDomesticOrders;
    use RefreshDatabase;

    public function test_compute_and_store_aggregates_paid_order_metrics(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-08-03 15:00:00', config('app.timezone')));

        $order = $this->createDomesticOrder([
            'total' => 250,
            'subtotal' => 220,
            'discount_amount' => 20,
            'shipping_price' => 30,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Payment::query()->create([
            'user_id' => $order->user_id,
            'order_id' => $order->id,
            'paid_amount' => 250,
            'status' => PaymentStatus::COMPLETED,
            'provider' => PaymentProvider::IYZICO,
            'provider_payment_id' => 'pay-1',
        ]);

        $service = app(DailyAnalyticsService::class);
        $row = $service->computeAndStore(now());

        $this->assertInstanceOf(DailyAnalytic::class, $row);
        $this->assertSame('2026-08-03', $row->date->toDateString());
        $this->assertSame(1, $row->total_orders);
        $this->assertSame(1, $row->paid_orders);
        $this->assertSame(1, $row->new_customers);
        $this->assertSame(0, $row->returning_customers);
        $this->assertSame(1, $row->products_sold_quantity);
        $this->assertSame(1, $row->domestic_orders);
        $this->assertEquals('220.00', $row->gross_revenue);
        $this->assertEquals('250.00', $row->net_revenue);
        $this->assertEquals('250.00', $row->average_order_value);

        Carbon::setTestNow();
    }
}
