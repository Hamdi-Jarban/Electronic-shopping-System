<?php

namespace Database\Factories;

use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;

    public function definition(): array
    {
        return [
            'product_id' => null,
            'SKU' => fake()->unique()->regexify('[A-Z]{3}-[0-9]{5}'),
            'size_option' => fake()->optional(0.7)->randomElement(['Small', 'Medium', 'Large', 'XL', 'XXL']),
            'color_option' => fake()->optional(0.6)->randomElement(['أحمر', 'أزرق', 'أخضر', 'أسود', 'أبيض', 'أصفر']),
            'packaging' => fake()->optional(0.4)->randomElement(['صندوق', 'كيس', 'تغليف', 'بدون']),
            'price' => fake()->randomFloat(2, 10, 5000),
            'weight_kg' => fake()->optional(0.8)->randomFloat(3, 0.1, 50),
            'image_url' => fake()->optional(0.5)->imageUrl(640, 480, 'product', true),
        ];
    }
}
