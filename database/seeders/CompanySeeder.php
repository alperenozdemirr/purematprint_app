<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        $companies = [
            'Flaw Wear',
            'Unoıse',
            'Studio Noir',
            'Demir Mimarlık',
            'Kaya Coffee',
            'Atlas Reklam',
        ];

        foreach ($companies as $index => $name) {
            Company::query()->updateOrCreate(
                ['name' => $name],
                ['number' => $index + 1]
            );
        }
    }
}
