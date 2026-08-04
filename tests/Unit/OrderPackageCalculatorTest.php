<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Http\Services\OrderPackageCalculator;
use App\Http\Services\ShipinkConfigService;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\CreatesDomesticOrders;
use Tests\TestCase;

class OrderPackageCalculatorTest extends TestCase
{
    use CreatesDomesticOrders;
    use RefreshDatabase;

    public function test_calculates_stacked_package_from_product_dimensions(): void
    {
        Setting::current();

        config([
            'shipink.default_package.weight' => 1,
            'shipink.default_package.length' => 20,
            'shipink.default_package.width' => 15,
            'shipink.default_package.height' => 10,
        ]);

        $order = $this->createDomesticOrder();
        $product = $order->details->first()->product;
        $product->update([
            'shipping_weight' => 0.5,
            'shipping_length' => 10,
            'shipping_width' => 20,
            'shipping_height' => 5,
        ]);
        $order->details->first()->update(['quantity' => 2]);

        $result = app(OrderPackageCalculator::class)->calculate($order->fresh(['details.product']));

        $this->assertSame('calculated', $result['source']);
        $this->assertSame(20, $result['length']);
        $this->assertSame(10, $result['width']);
        $this->assertSame(10, $result['height']);
        $this->assertSame(1, $result['weight']);
        $this->assertSame(0.67, $result['desi']);
    }

    public function test_falls_back_to_defaults_when_dimensions_missing(): void
    {
        Setting::saveSingleton([
            'shipink_default_weight' => 2,
            'shipink_default_length' => 30,
            'shipink_default_width' => 20,
            'shipink_default_height' => 12,
        ]);

        $order = $this->createDomesticOrder();
        $result = app(OrderPackageCalculator::class)->calculate($order->fresh(['details.product']));

        $this->assertSame('default', $result['source']);
        $this->assertSame(2, $result['weight']);
        $this->assertSame(30, $result['length']);
        $this->assertSame(20, $result['width']);
        $this->assertSame(12, $result['height']);
        $this->assertNotEmpty($result['warnings']);
    }
}
