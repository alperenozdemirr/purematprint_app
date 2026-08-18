<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Enums\AddressScope;
use App\Enums\ShippingMode;
use App\Http\Services\CurrencyConversionService;
use App\Http\Services\OrderPricingService;
use App\Models\Address;
use App\Models\Setting;
use App\Models\ShoppingCart;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Mockery;
use Tests\Support\CreatesDomesticOrders;
use Tests\TestCase;

class OrderPricingInternationalTest extends TestCase
{
    use CreatesDomesticOrders;
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_international_shipping_uses_separate_fee(): void
    {
        Setting::saveSingleton([
            'shipping_mode' => ShippingMode::PAID->value,
            'shipping_fee' => 49,
            'international_shipping_mode' => ShippingMode::PAID->value,
            'international_shipping_fee' => 199,
            'shipping_first_order_free' => false,
        ]);

        $order = $this->createDomesticOrder();
        $user = User::query()->findOrFail($order->user_id);

        $internationalAddress = Address::query()->findOrFail($order->address_id);
        $internationalAddress->update([
            'scope' => AddressScope::INTERNATIONAL,
            'country' => 'Germany',
            'state' => 'Berlin',
            'city_name' => 'Berlin',
            'postal_code' => '10115',
        ]);

        ShoppingCart::query()->create([
            'user_id' => $user->id,
            'product_id' => $order->details->first()->product_id,
            'quantity' => 1,
            'property_signature' => '',
        ]);

        $cartItems = ShoppingCart::query()->with('product')->where('user_id', $user->id)->get();

        $this->mock(CurrencyConversionService::class, function ($mock): void {
            $mock->shouldReceive('eurToTry')->andReturn(40.0);
            $mock->shouldReceive('eurPerTry')->andReturn(0.025);
            $mock->shouldReceive('tryToEur')->andReturnUsing(fn (float $try) => round($try / 40, 2));
            $mock->shouldReceive('quoteCartEurTotal')->andReturnUsing(function ($items, array $summary, callable $unitPriceForItem) {
                $subtotal = (float) ($summary['subtotal'] ?? 0);
                $shipping = (float) ($summary['shippingCost'] ?? 0);

                return round(($subtotal + $shipping) / 40, 2);
            });
        });

        $summary = app(OrderPricingService::class)->calculate($cartItems, $user, $internationalAddress);

        $this->assertTrue($summary['isInternational']);
        $this->assertSame(199.0, $summary['shippingCost']);
        $this->assertSame('EUR', $summary['chargeCurrency']);
    }

    public function test_first_order_free_shipping_applies(): void
    {
        Setting::saveSingleton([
            'shipping_mode' => ShippingMode::PAID->value,
            'shipping_fee' => 49,
            'international_shipping_mode' => ShippingMode::PAID->value,
            'international_shipping_fee' => 199,
            'shipping_first_order_free' => true,
        ]);

        $user = User::factory()->create(['phone' => '5321112233']);
        $product = $this->createDomesticOrder()->details->first()->product;

        ShoppingCart::query()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'property_signature' => '',
        ]);

        $cartItems = ShoppingCart::query()->with('product')->where('user_id', $user->id)->get();
        $summary = app(OrderPricingService::class)->calculate($cartItems, $user);

        $this->assertTrue($summary['shippingFree']);
        $this->assertSame(0.0, $summary['shippingCost']);
    }

    public function test_first_order_free_does_not_apply_after_order_exists(): void
    {
        Setting::saveSingleton([
            'shipping_mode' => ShippingMode::PAID->value,
            'shipping_fee' => 49,
            'shipping_first_order_free' => true,
        ]);

        $order = $this->createDomesticOrder();
        $user = User::query()->findOrFail($order->user_id);

        ShoppingCart::query()->create([
            'user_id' => $user->id,
            'product_id' => $order->details->first()->product_id,
            'quantity' => 1,
            'property_signature' => '',
        ]);

        $cartItems = ShoppingCart::query()->with('product')->where('user_id', $user->id)->get();
        $summary = app(OrderPricingService::class)->calculate($cartItems, $user);

        $this->assertFalse($summary['shippingFree']);
        $this->assertSame(49.0, $summary['shippingCost']);
    }

    public function test_international_pricing_marks_rate_unavailable(): void
    {
        Cache::forget('fx_eur_try_rate');

        Http::fake([
            'https://www.tcmb.gov.tr/kurlar/today.xml' => Http::response('', 503),
            'https://open.er-api.com/v6/latest/EUR' => Http::response('', 503),
        ]);

        Setting::saveSingleton([
            'international_shipping_mode' => ShippingMode::PAID->value,
            'international_shipping_fee' => 199,
        ]);

        $order = $this->createDomesticOrder();
        $user = User::query()->findOrFail($order->user_id);

        $internationalAddress = Address::query()->findOrFail($order->address_id);
        $internationalAddress->update([
            'scope' => AddressScope::INTERNATIONAL,
            'country' => 'Germany',
            'state' => 'Berlin',
            'city_name' => 'Berlin',
            'postal_code' => '10115',
        ]);

        ShoppingCart::query()->create([
            'user_id' => $user->id,
            'product_id' => $order->details->first()->product_id,
            'quantity' => 1,
            'property_signature' => '',
        ]);

        $cartItems = ShoppingCart::query()->with('product')->where('user_id', $user->id)->get();
        $summary = app(OrderPricingService::class)->calculate($cartItems, $user, $internationalAddress->fresh());

        $this->assertTrue($summary['exchangeRateUnavailable'] ?? false);
        $this->assertArrayNotHasKey('chargeTotal', $summary);
    }
}
