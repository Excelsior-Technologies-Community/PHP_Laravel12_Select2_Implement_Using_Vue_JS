<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            ['name' => 'Electronics', 'color' => '#3B82F6'],
            ['name' => 'Clothing', 'color' => '#10B981'],
            ['name' => 'Books', 'color' => '#F59E0B'],
            ['name' => 'Home & Kitchen', 'color' => '#8B5CF6'],
            ['name' => 'Sports & Outdoors', 'color' => '#EC4899'],
            ['name' => 'Toys & Games', 'color' => '#06B6D4'],
            ['name' => 'Food & Groceries', 'color' => '#14B8A6'],
            ['name' => 'Beauty & Care', 'color' => '#6366F1'],
            ['name' => 'Vue.js', 'color' => '#41B883'],
            ['name' => 'VueJS', 'color' => '#34495E'], // For testing tag merge!
            ['name' => 'Deprecated Tag 1', 'color' => '#94A3B8'], // Orphan tag
            ['name' => 'Deprecated Tag 2', 'color' => '#64748B'], // Orphan tag
        ];

        foreach ($tags as $tag) {
            Tag::firstOrCreate(['name' => $tag['name']], ['color' => $tag['color']]);
        }
    }
}