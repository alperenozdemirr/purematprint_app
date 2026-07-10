<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddressSeeder extends Seeder
{
    public function run(): void
    {
        $addresses = [
            [
                'user_id' => 1,
                'title' => 'Ev Adresi',
                'content' => 'Atatürk Mah. Cumhuriyet Cad. No:12 Daire:4',
                'city_id' => 40,
                'county_id' => 449,
            ],
            [
                'user_id' => 1,
                'title' => 'İş Adresi',
                'content' => 'Kozyatağı Mah. İş Merkezi Sok. No:8 Kat:3',
                'city_id' => 40,
                'county_id' => 449,
            ],
            [
                'user_id' => 2,
                'title' => 'Ev Adresi',
                'content' => 'Yeni Mahalle 145. Sokak No:7',
                'city_id' => 7,
                'county_id' => 74,
            ],
            [
                'user_id' => 3,
                'title' => 'Teslimat Adresi',
                'content' => 'Güneşli Bulvarı No:22',
                'city_id' => 40,
                'county_id' => 451,
            ],
        ];

        foreach ($addresses as $address) {
            DB::table('addresses')->insert(array_merge($address, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
