<?php

declare(strict_types=1);

namespace Tests\Support;

use App\Enums\AddressScope;
use App\Enums\InvoiceType;
use App\Enums\OrderStatus;
use App\Enums\Status;
use App\Models\Address;
use App\Models\Category;
use App\Models\City;
use App\Models\County;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;

trait CreatesDomesticOrders
{
    protected function createDomesticOrder(array $orderAttributes = []): Order
    {
        $user = User::factory()->create([
            'phone' => '5321234567',
        ]);

        $city = City::query()->create([
            'code' => 34,
            'name' => 'İstanbul',
        ]);

        $county = County::query()->create([
            'city_id' => $city->id,
            'name' => 'Kadıköy',
        ]);

        $address = Address::query()->create([
            'user_id' => $user->id,
            'scope' => AddressScope::DOMESTIC,
            'title' => 'Ev',
            'content' => 'Test Sokak No 1',
            'city_id' => $city->id,
            'county_id' => $county->id,
            'postal_code' => '34000',
        ]);

        $category = Category::query()->create([
            'name' => 'Test Kategori',
            'slug' => 'test-kategori-'.uniqid(),
        ]);

        $product = Product::query()->create([
            'title' => 'Test Ürün',
            'slug' => 'test-urun-'.uniqid(),
            'code' => 'TU-'.uniqid(),
            'price' => 100,
            'category_id' => $category->id,
            'status' => Status::ACTIVE,
        ]);

        $order = Order::query()->create(array_merge([
            'user_id' => $user->id,
            'code' => 'ORD-TEST-'.uniqid(),
            'total' => 100,
            'subtotal' => 100,
            'address_id' => $address->id,
            'invoice_address_id' => $address->id,
            'invoice_type' => InvoiceType::INDIVIDUAL,
            'status' => OrderStatus::PREPARING,
        ], $orderAttributes));

        OrderDetail::query()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'price' => 100,
            'quantity' => 1,
        ]);

        return $order->fresh(['user', 'address.city', 'address.county', 'details.product']);
    }
}
