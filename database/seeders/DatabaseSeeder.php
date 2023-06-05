<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'email' => "contact@weboven.ma",
            'role' => \App\Models\User::ROLE_SUPER_ADMIN,
            'first_name' => 'abdessamad',
            'last_name' => 'el fedali',
            'phone_number' => '0627018957',
            'address' => 'hay salam',
            'city' => 'casablanca',
            'zip_code' => '20250',
            'country' => 'maroc',
            'photo' => 'https://lh3.googleusercontent.com/a/AAcHTtdnjIRxOJbVY7jQn8e4aqwb_cs-2_OUIE5_MqgR=s96-c',
            'is_enabled' => true,
            'email_verified_at' => now(),
            'phone_number_verified_at' => now(),


        ]);
        \App\Models\User::factory(100)->create();


        $this->call([
            CategorySeeder::class,
            TagSeeder::class,
            ShopSeeder::class,
        ]);
    }
}
