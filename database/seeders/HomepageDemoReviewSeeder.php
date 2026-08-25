<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\HomepageDemoReview;
use Illuminate\Database\Seeder;

class HomepageDemoReviewSeeder extends Seeder
{
    public function run(): void
    {
        if (HomepageDemoReview::query()->exists()) {
            return;
        }

        $items = [
            [
                'quote' => 'İşlerine gerçekten özen gösteriyorlar. Mağazamızın varlığını müşteriler içeri girmeden yükseltiyor.',
                'author' => 'Elif Yılmaz, Studio Noir',
            ],
            [
                'quote' => 'Zamansız sadelik ve profesyonellik bir arada. Her projede güvenle yönlendiriyorum.',
                'author' => 'Can Demir, Demir Mimarlık',
            ],
            [
                'quote' => 'Yoğun dönemlerde PureMatPrint\'e güveniyoruz. Hızlı prova, kusursuz teslimat.',
                'author' => 'Zeynep Kaya, Kaya Coffee',
            ],
            [
                'quote' => 'Marka kimliğimizi mekâna taşıyan güvenilir bir ortak. Detaylara verdikleri önem fark yaratıyor.',
                'author' => 'Selin Arslan, Atlas Reklam',
            ],
        ];

        foreach ($items as $index => $item) {
            HomepageDemoReview::create([
                'quote' => $item['quote'],
                'author' => $item['author'],
                'stars' => 5,
                'sort_order' => $index + 1,
                'is_visible' => true,
            ]);
        }
    }
}
