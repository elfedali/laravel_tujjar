<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    private $tags_list =
    [
        [
            "name" => "Takeaway",
        ],
        [
            "name" => "Delivery",
        ],
        [
            "name" => "Dine-in",
        ],
    ];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->tags_list as $tag) {
            \App\Models\Tag::create($tag);
        }
    }
}
