<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(3, true),
            'description' => fake()->optional(0.8)->paragraph(3),
            'brand_id' => null,
            'base_image_url' => fake()->optional(0.6)->imageUrl(640, 480, 'product', true),
            'is_active' => fake()->boolean(90),
        ];
    }
}
