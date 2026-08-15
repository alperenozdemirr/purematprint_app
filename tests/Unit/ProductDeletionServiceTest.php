<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Enums\Status;
use App\Http\Services\ProductDeletionService;
use App\Models\Collection;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ShoppingCart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RuntimeException;
use Tests\Support\CreatesDomesticOrders;
use Tests\TestCase;

class ProductDeletionServiceTest extends TestCase
{
    use CreatesDomesticOrders;
    use RefreshDatabase;

    public function test_delete_fully_removes_product_without_orders(): void
    {
        $order = $this->createDomesticOrder();
        $categoryId = (int) $order->details->first()->product->category_id;

        $product = Product::query()->create([
            'title' => 'Silinecek Ürün',
            'slug' => 'silinecek-urun',
            'code' => 'DEL-001',
            'price' => 100,
            'stock_count' => 5,
            'category_id' => $categoryId,
            'status' => Status::ACTIVE,
        ]);

        ShoppingCart::query()->create([
            'user_id' => $order->user_id,
            'product_id' => $product->id,
            'quantity' => 1,
            'property_signature' => '',
        ]);

        $collection = Collection::query()->create([
            'title' => 'Test Koleksiyon',
            'slug' => 'test-koleksiyon',
            'status' => Status::ACTIVE,
        ]);
        $collection->products()->attach($product->id, ['number' => 1]);

        app(ProductDeletionService::class)->deleteFully($product);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
        $this->assertDatabaseMissing('shopping_carts', ['product_id' => $product->id]);
        $this->assertDatabaseMissing('collection_products', ['product_id' => $product->id]);
    }

    public function test_delete_fully_blocks_when_order_history_exists(): void
    {
        $order = $this->createDomesticOrder();
        $productId = (int) $order->details->first()->product_id;

        $this->expectException(RuntimeException::class);

        app(ProductDeletionService::class)->deleteFully(Product::query()->findOrFail($productId));
    }

    public function test_deactivate_sets_product_passive(): void
    {
        $product = Product::query()->create([
            'title' => 'Pasif Olacak',
            'slug' => 'pasif-olacak',
            'code' => 'PAS-001',
            'price' => 50,
            'stock_count' => 1,
            'category_id' => $this->createDomesticOrder()->details->first()->product->category_id,
            'status' => Status::ACTIVE,
            'featured_status' => true,
            'introduction_status' => true,
        ]);

        app(ProductDeletionService::class)->deactivate($product->fresh());

        $product->refresh();
        $this->assertSame(Status::PASSIVE, $product->status);
        $this->assertFalse($product->featured_status);
        $this->assertFalse($product->introduction_status);
        $this->assertDatabaseHas('products', ['id' => $product->id]);
    }

    public function test_has_order_history_detects_order_detail(): void
    {
        $order = $this->createDomesticOrder();
        $product = Product::query()->findOrFail($order->details->first()->product_id);

        $this->assertTrue(app(ProductDeletionService::class)->hasOrderHistory($product));

        OrderDetail::query()->where('product_id', $product->id)->delete();

        $this->assertFalse(app(ProductDeletionService::class)->hasOrderHistory($product->fresh()));
    }
}
