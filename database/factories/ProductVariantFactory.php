<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductVariantFactory extends Factory
{
    public function definition(): array
    {
        $attributes = [
            'color' => fake()->safeColorName(),
            'size' => fake()->randomElement(['صغير', 'متوسط', 'كبير', 'XL']),
            'weight' => fake()->numberBetween(200, 2000) . 'g'
        ];
        return [
            'product_id' => null,
            'sku' => strtoupper(fake()->unique()->ean8()),
            'price' => fake()->randomFloat(2, 10, 500),
            'compare_at_price' => fake()->optional(0.6)->randomFloat(2, 15, 600),
            'attributes' => json_encode($attributes, JSON_UNESCAPED_UNICODE),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}