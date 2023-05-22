<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    private $category_list = [
        [
            'name' => 'Appliances',
        ],
        [
            'name' => 'Apps & Games',
        ],
        [
            'name' => 'Arts, Crafts, & Sewing',
        ],
        [
            'name' => 'Automotive Parts & Accessories',
        ],
        [
            'name' => 'Baby',
        ],
        [
            'name' => 'Beauty & Personal Care',
        ],
        [
            'name' => 'Books',
        ],

        [
            'name' => 'Cell Phones & Accessories',
        ],
        [
            'name' => 'Clothing, Shoes and Jewelry',
        ],
        [
            'name' => 'Collectibles & Fine Art',
        ],
        [
            'name' => 'Computers',
        ],
        [
            'name' => 'Electronics',
        ],
        [
            'name' => 'Garden & Outdoor',
        ],
        [
            'name' => 'Grocery & Gourmet Food',
        ],
        [
            'name' => 'Handmade',
        ],
        [
            'name' => 'Health, Household & Baby Care',
        ],
        [
            'name' => 'Home & Kitchen',
        ],
        [
            'name' => 'Industrial & Scientific',
        ],
        [
            'name' => 'Luggage & Travel Gear',
        ],
        [
            'name' => 'Movies & TV',
        ],
        [
            'name' => 'Musical Instruments',
        ],
        [
            'name' => 'Office Products',
        ],
        [
            'name' => 'Pet Supplies',
        ],
        [
            'name' => 'Premium Beauty',
        ],
        [
            'name' => 'Sports & Outdoors',
        ],
        [
            'name' => 'Tools & Home Improvement',
        ],
        [
            'name' => 'Toys & Games',
        ],
        [
            'name' => 'Video Games',
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        foreach ($this->category_list as $category) {
            \App\Models\Category::create($category);
        }
    }
}
