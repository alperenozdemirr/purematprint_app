<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Status;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categoryId = Category::query()->value('id') ?? 1;

        $products = [
            [
                'title' => 'LED Lightbox Tabela',
                'code' => '482917365',
                'price' => 2499.00,
                'stock_count' => 15,
            ],
            [
                'title' => 'Roll-Up Banner 85x200',
                'code' => '739104826',
                'price' => 899.00,
                'stock_count' => 30,
            ],
            [
                'title' => 'Magnet Afiş Seti A3',
                'code' => '105847293',
                'price' => 349.00,
                'stock_count' => 50,
            ],
        ];

        foreach ($products as $product) {
            DB::table('products')->insert([
                'title' => $product['title'],
                'slug' => Str::slug($product['title']) . '-' . $product['code'],
                'code' => $product['code'],
                'price' => $product['price'],
                'stock_count' => $product['stock_count'],
                'category_id' => $categoryId,
                'status' => Status::ACTIVE->value,
                'featured_status' => true,
                'introduction_status' => false,
                'description' => $product['title'] . ' için örnek ürün açıklaması.',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
