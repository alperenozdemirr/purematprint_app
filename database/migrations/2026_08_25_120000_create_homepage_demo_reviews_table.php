<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homepage_demo_reviews', function (Blueprint $table) {
            $table->id();
            $table->text('quote');
            $table->string('author', 160);
            $table->unsignedTinyInteger('stars')->default(5);
            $table->unsignedBigInteger('image_id')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });

        $now = now();
        $items = [
            ['quote' => 'İşlerine gerçekten özen gösteriyorlar. Mağazamızın varlığını müşteriler içeri girmeden yükseltiyor.', 'author' => 'Elif Yılmaz, Studio Noir'],
            ['quote' => 'Zamansız sadelik ve profesyonellik bir arada. Her projede güvenle yönlendiriyorum.', 'author' => 'Can Demir, Demir Mimarlık'],
            ['quote' => 'Yoğun dönemlerde PureMatPrint\'e güveniyoruz. Hızlı prova, kusursuz teslimat.', 'author' => 'Zeynep Kaya, Kaya Coffee'],
            ['quote' => 'Marka kimliğimizi mekâna taşıyan güvenilir bir ortak. Detaylara verdikleri önem fark yaratıyor.', 'author' => 'Selin Arslan, Atlas Reklam'],
        ];

        foreach ($items as $index => $item) {
            DB::table('homepage_demo_reviews')->insert([
                'quote' => $item['quote'],
                'author' => $item['author'],
                'stars' => 5,
                'sort_order' => $index + 1,
                'is_visible' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_demo_reviews');
    }
};
