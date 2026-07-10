<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            ['name' => 'Tabela & Afiş','slug' => 'tabela-afis01','parent_id'=>null],
            ['name' => 'Menü & Display','slug' => 'menu-display02','parent_id'=>null],
            ['name' => 'Baskı & Marka','slug' => 'baski-marka03','parent_id'=>null],

            ['name' => 'Açık Hava Tabelası','slug' => 'acik-hava-tabelasi04','parent_id'=>1],
            ['name' => 'A-Frame Tabela','slug' => 'a-frame-tabela05','parent_id'=>1],
            ['name' => 'LED Lightbox','slug' => 'led-lightbox06','parent_id'=>1],
            ['name' => 'Roll-Up Banner','slug' => 'roll-up-banner07','parent_id'=>1],

            ['name' => 'Magnet Afiş Seti','slug' => 'magnet-afis-seti08','parent_id'=>2],
            ['name' => 'Roll-Up Banner','slug' => 'roll-up-banner09','parent_id'=>2],

            ['name' => 'Kartvizit','slug' => 'kartvizit-10','parent_id'=>3],
            ['name' => 'Kurumsal Kimlik','slug' => 'kurumsal-kimlik-11','parent_id'=>3],
            ['name' => 'Ambalaj','slug' => 'ambalaj-12','parent_id'=>3],
            ['name' => 'Dijital Baskı','slug' => 'dijital-baski-13','parent_id'=>3],





        ]);
    }
}