<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            ['id' => 1, 'name' => 'ADANA',  'code' => '1'],
            ['id' => 2, 'name' => 'ADIYAMAN',  'code' => '2'],
            ['id' => 3, 'name' => 'AFYONKARAHİSAR',  'code' => '3'],
            ['id' => 4, 'name' => 'AĞRI',  'code' => '4'],
            ['id' => 5, 'name' => 'AKSARAY',  'code' => '68'],
            ['id' => 6, 'name' => 'AMASYA',  'code' => '5'],
            ['id' => 7, 'name' => 'ANKARA',  'code' => '6'],
            ['id' => 8, 'name' => 'ANTALYA',  'code' => '7'],
            ['id' => 9, 'name' => 'ARDAHAN',  'code' => '75'],
            ['id' => 10, 'name' => 'ARTVİN', 'code' =>  '8'],
            ['id' => 11, 'name' => 'AYDIN', 'code' =>  '9'],
            ['id' => 12, 'name' => 'BALIKESİR', 'code' =>  '10'],
            ['id' => 13, 'name' => 'BARTIN', 'code' =>  '74'],
            ['id' => 14, 'name' => 'BATMAN', 'code' =>  '72'],
            ['id' => 15, 'name' => 'BAYBURT', 'code' =>  '69'],
            ['id' => 16, 'name' => 'BİLECİK', 'code' =>  '11'],
            ['id' => 17, 'name' => 'BİNGÖL', 'code' =>  '12'],
            ['id' => 18, 'name' => 'BİTLİS', 'code' =>  '13'],
            ['id' => 19, 'name' => 'BOLU', 'code' =>  '14'],
            ['id' => 20, 'name' => 'BURDUR', 'code' =>  '15'],
            ['id' => 21, 'name' => 'BURSA', 'code' =>  '16'],
            ['id' => 22, 'name' => 'ÇANAKKALE', 'code' =>  '17'],
            ['id' => 23, 'name' => 'ÇANKIRI', 'code' =>  '18'],
            ['id' => 24, 'name' => 'ÇORUM', 'code' =>  '19'],
            ['id' => 25, 'name' => 'DENİZLİ', 'code' =>  '20'],
            ['id' => 26, 'name' => 'DİYARBAKIR', 'code' =>  '21'],
            ['id' => 27, 'name' => 'DÜZCE', 'code' =>  '81'],
            ['id' => 28, 'name' => 'EDİRNE', 'code' =>  '22'],
            ['id' => 29, 'name' => 'ELAZIĞ', 'code' =>  '23'],
            ['id' => 30, 'name' => 'ERZİNCAN', 'code' =>  '24'],
            ['id' => 31, 'name' => 'ERZURUM', 'code' =>  '25'],
            ['id' => 32, 'name' => 'ESKİŞEHİR', 'code' =>  '26'],
            ['id' => 33, 'name' => 'GAZİANTEP', 'code' =>  '27'],
            ['id' => 34, 'name' => 'GİRESUN', 'code' =>  '28'],
            ['id' => 35, 'name' => 'GÜMÜŞHANE', 'code' =>  '29'],
            ['id' => 36, 'name' => 'HAKKARİ', 'code' =>  '30'],
            ['id' => 37, 'name' => 'HATAY', 'code' =>  '31'],
            ['id' => 38, 'name' => 'IĞDIR', 'code' =>  '76'],
            ['id' => 39, 'name' => 'ISPARTA', 'code' =>  '32'],
            ['id' => 40, 'name' => 'İSTANBUL', 'code' =>  '34'],
            ['id' => 41, 'name' => 'İZMİR', 'code' =>  '35'],
            ['id' => 42, 'name' => 'KAHRAMANMARAŞ', 'code' =>  '46'],
            ['id' => 43, 'name' => 'KARABÜK', 'code' =>  '78'],
            ['id' => 44, 'name' => 'KARAMAN', 'code' =>  '70'],
            ['id' => 45, 'name' => 'KARS', 'code' =>  '36'],
            ['id' => 46, 'name' => 'KASTAMONU', 'code' =>  '37'],
            ['id' => 47, 'name' => 'KAYSERİ', 'code' =>  '38'],
            ['id' => 48, 'name' => 'KIRIKKALE', 'code' =>  '71'],
            ['id' => 49, 'name' => 'KIRKLARELİ', 'code' =>  '39'],
            ['id' => 50, 'name' => 'KIRŞEHİR', 'code' =>  '40'],
            ['id' => 51, 'name' => 'KİLİS', 'code' =>  '79'],
            ['id' => 52, 'name' => 'KOCAELİ', 'code' =>  '41'],
            ['id' => 53, 'name' => 'KONYA', 'code' =>  '42'],
            ['id' => 54, 'name' => 'KÜTAHYA', 'code' =>  '43'],
            ['id' => 55, 'name' => 'MALATYA', 'code' =>  '44'],
            ['id' => 56, 'name' => 'MANİSA', 'code' =>  '45'],
            ['id' => 57, 'name' => 'MARDİN', 'code' =>  '47'],
            ['id' => 58, 'name' => 'MERSİN', 'code' =>  '33'],
            ['id' => 59, 'name' => 'MUĞLA', 'code' =>  '48'],
            ['id' => 60, 'name' => 'MUŞ', 'code' =>  '49'],
            ['id' => 61, 'name' => 'NEVŞEHİR', 'code' =>  '50'],
            ['id' => 62, 'name' => 'NİĞDE', 'code' =>  '51'],
            ['id' => 63, 'name' => 'ORDU', 'code' =>  '52'],
            ['id' => 64, 'name' => 'OSMANİYE', 'code' =>  '80'],
            ['id' => 65, 'name' => 'RİZE', 'code' =>  '53'],
            ['id' => 66, 'name' => 'SAKARYA', 'code' =>  '54'],
            ['id' => 67, 'name' => 'SAMSUN', 'code' =>  '55'],
            ['id' => 68, 'name' => 'SİİRT', 'code' =>  '56'],
            ['id' => 69, 'name' => 'SİNOP', 'code' =>  '57'],
            ['id' => 70, 'name' => 'SİVAS', 'code' =>  '58'],
            ['id' => 71, 'name' => 'ŞANLIURFA', 'code' =>  '63'],
            ['id' => 72, 'name' => 'ŞIRNAK', 'code' =>  '73'],
            ['id' => 73, 'name' => 'TEKİRDAĞ', 'code' =>  '59'],
            ['id' => 74, 'name' => 'TOKAT', 'code' =>  '60'],
            ['id' => 75, 'name' => 'TRABZON', 'code' =>  '61'],
            ['id' => 76, 'name' => 'TUNCELİ', 'code' =>  '62'],
            ['id' => 77, 'name' => 'UŞAK', 'code' =>  '64'],
            ['id' => 78, 'name' => 'VAN', 'code' =>  '65'],
            ['id' => 79, 'name' => 'YALOVA', 'code' =>  '77'],
            ['id' => 80, 'name' => 'YOZGAT', 'code' =>  '66'],
            ['id' => 81, 'name' => 'ZONGULDAK', 'code' =>  '67']
        ];

        DB::table('cities')->insert($cities);
    }
}
