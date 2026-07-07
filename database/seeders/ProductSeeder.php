<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $tags = Tag::all();

        $products = [
            ['name' => 'iPhone 14', 'price' => 800],
            ['name' => 'Nike Shoes', 'price' => 120],
            ['name' => 'Harry Potter Book', 'price' => 30],
            ['name' => 'Samsung TV', 'price' => 600],
            ['name' => 'Gaming Mouse', 'price' => 50],
            ['name' => 'Backpack', 'price' => 40],
        ];

        foreach ($products as $index => $data) {

            $product = Product::create([
                'sku' => 'PRD-' . str_pad($index + 1, 5, '0', STR_PAD_LEFT),
                'name' => $data['name'],
                'price' => $data['price'],
            ]);

            // Attach 2 random tags
            $randomTags = $tags->random(2)->pluck('id');

            $product->tags()->attach($randomTags);
        }
    }
}