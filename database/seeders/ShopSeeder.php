<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = \App\Models\Category::all();
        $tags = \App\Models\Tag::all();

        $shops = \App\Models\Shop::factory(200)->hasAttached($categories->random(rand(1, 28)))->hasAttached($tags->random(rand(1, 3)))->create();

        // Create 10 shops with alredy existing categories and tags
        // \App\Models\Shop::factory(10)->hasAttached(
        //     \App\Models\Category::factory()->count(3)
        // )->hasAttached(
        //     \App\Models\Tag::factory()->count(3)
        // )->create();
    }
}
