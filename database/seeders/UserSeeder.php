<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enums\UserType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            ['name' => 'Alp Eren Özdemir','email'=>'qeuruck54@gmail.com','phone'=>'05458145563', 'password' => Hash::make('12345678'),'type' => UserType::USER],
            ['name' => 'Test User1','email'=>'test_user1@gmail.com','phone'=>'05458145564', 'password' => Hash::make('12345678'),'type' => UserType::USER],
            ['name' => 'Test User2','email'=>'test_user2@gmail.com','phone'=>'05458145565', 'password' => Hash::make('12345678'),'type' => UserType::USER],
            ['name' => 'Test User3','email'=>'test_user3@gmail.com','phone'=>'05458145566', 'password' => Hash::make('12345678'),'type' => UserType::USER],
            ['name' => 'administrator','email'=>'admin@gmail.com','phone'=>'05458145567', 'password' => Hash::make('12345678'),'type' => UserType::ADMIN],
        ]);
    }
}
