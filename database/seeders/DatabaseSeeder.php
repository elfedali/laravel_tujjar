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
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'email' => "webmaster@tujjar.ma",
            'role' => \App\Models\User::ROLE_SUPER_ADMIN,
            'first_name' => 'abdessamad',
            'last_name' => 'el fedali',
            'phone_number' => '0627018957',

        ]);

        $this->call([
            CategorySeeder::class,
            TagSeeder::class,
            ShopSeeder::class,
        ]);
    }
}
