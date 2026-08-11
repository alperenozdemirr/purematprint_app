<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\FaqGroup;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            [
                'title' => 'Sipariş & Ödeme',
                'number' => 1,
                'faqs' => [
                    [
                        'title' => 'Nasıl sipariş verebilirim?',
                        'content' => "Ürün sayfasından seçeneklerinizi belirleyip sepete ekleyebilir, ardından ödeme adımına geçerek siparişinizi tamamlayabilirsiniz.\n\nKayıtlı kullanıcılar sipariş geçmişlerini hesap panelinden takip edebilir.",
                        'number' => 1,
                        'fixed_status' => true,
                    ],
                    [
                        'title' => 'Hangi ödeme yöntemlerini kabul ediyorsunuz?',
                        'content' => 'Kredi kartı ve güvenli online ödeme altyapıları ile ödeme alıyoruz. Ödeme ekranında kullanılabilir yöntemler listelenir.',
                        'number' => 2,
                        'fixed_status' => true,
                    ],
                ],
            ],
            [
                'title' => 'Üretim & Teslimat',
                'number' => 2,
                'faqs' => [
                    [
                        'title' => 'Teslimat süresi ne kadar?',
                        'content' => "Standart üretim ve teslimat süresi ürüne göre değişmekle birlikte çoğu sipariş 3–5 iş günü içinde kargoya verilir.\n\nHızlı üretim seçeneği sunulan ürünlerde bu süre kısalabilir.",
                        'number' => 1,
                        'fixed_status' => true,
                    ],
                    [
                        'title' => 'Kargo ücreti var mı?',
                        'content' => 'Belirli tutarın üzerindeki siparişlerde ücretsiz kargo uygulanır. Sepet ve ödeme adımında güncel kargo bilgisi gösterilir.',
                        'number' => 2,
                        'fixed_status' => false,
                    ],
                    [
                        'title' => 'Dijital prova/onay süreci nasıl işler?',
                        'content' => 'Kişiye özel üretimlerde baskı öncesi dijital prova paylaşılır. Onayınız olmadan üretime geçilmez.',
                        'number' => 3,
                        'fixed_status' => true,
                    ],
                ],
            ],
        ];

        foreach ($groups as $groupData) {
            $faqs = $groupData['faqs'];
            unset($groupData['faqs']);

            $group = FaqGroup::query()->create($groupData);

            foreach ($faqs as $faqData) {
                Faq::query()->create([
                    ...$faqData,
                    'group_id' => $group->id,
                ]);
            }
        }
    }
}
