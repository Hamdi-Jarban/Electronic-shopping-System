<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductImageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id' => null,
            'variant_id' => null,
            'image_path' => 'products/' . fake()->image('public/storage/products', 640, 480, null, false),
            'is_featured' => fake()->boolean(20),
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}