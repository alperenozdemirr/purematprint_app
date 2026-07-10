<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $orders = [
            [
                'user_id' => 1,
                'code' => 'ORD-20260709',
                'subtotal' => 3197.00,
                'total' => 3197.00,
                'status' => OrderStatus::PREPARING->value,
                'address_id' => 1,
                'invoice_address_id' => 2,
                'note' => 'Kapıda teslim edilsin.',
                'items' => [
                    ['product_id' => 1, 'price' => 2499.00, 'quantity' => 1],
                    ['product_id' => 3, 'price' => 349.00, 'quantity' => 2],
                ],
            ],
            [
                'user_id' => 2,
                'code' => 'ORD-20260708',
                'subtotal' => 899.00,
                'total' => 899.00,
                'status' => OrderStatus::SHIPPED->value,
                'address_id' => 3,
                'invoice_address_id' => 3,
                'note' => null,
                'items' => [
                    ['product_id' => 2, 'price' => 899.00, 'quantity' => 1],
                ],
            ],
            [
                'user_id' => 1,
                'code' => 'ORD-20260705',
                'subtotal' => 2499.00,
                'total' => 2499.00,
                'status' => OrderStatus::COMPLETED->value,
                'address_id' => 1,
                'invoice_address_id' => 1,
                'invoice_status' => true,
                'note' => null,
                'items' => [
                    ['product_id' => 1, 'price' => 2499.00, 'quantity' => 1],
                ],
            ],
            [
                'user_id' => 3,
                'code' => 'ORD-20260704',
                'subtotal' => 349.00,
                'total' => 349.00,
                'status' => OrderStatus::PREPARING->value,
                'address_id' => 4,
                'invoice_address_id' => 4,
                'note' => 'Hızlı teslimat isteniyor.',
                'items' => [
                    ['product_id' => 3, 'price' => 349.00, 'quantity' => 1],
                ],
            ],
        ];

        foreach ($orders as $orderData) {
            $items = $orderData['items'];
            unset($orderData['items']);

            $orderId = DB::table('orders')->insertGetId(array_merge([
                'is_discount_applied' => false,
                'discount_slice' => 0,
                'shipping_is_free' => true,
                'shipping_price' => null,
                'invoice_status' => $orderData['invoice_status'] ?? false,
                'created_at' => now()->subDays(rand(1, 5)),
                'updated_at' => now(),
            ], collect($orderData)->except('invoice_status')->toArray()));

            $subtotal = 0;
            foreach ($items as $item) {
                if ($item['quantity'] <= 0) {
                    continue;
                }

                DB::table('order_details')->insert([
                    'order_id' => $orderId,
                    'product_id' => $item['product_id'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $subtotal += $item['price'] * $item['quantity'];
            }

            DB::table('orders')->where('id', $orderId)->update([
                'subtotal' => $subtotal,
                'total' => $subtotal,
            ]);

            DB::table('payments')->insert([
                    'user_id' => $orderData['user_id'],
                    'order_id' => $orderId,
                    'paid_amount' => $subtotal,
                    'status' => PaymentStatus::COMPLETED->value,
                    'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
