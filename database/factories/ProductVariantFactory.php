<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductVariantFactory extends Factory
{
    public function definition(): array
    {
        $price = $this->faker->randomFloat(2, 10, 1000);
        return [
            'product_id' => \DB::table('products')->inRandomOrder()->first()?->id ?? 1,
            'sku' => strtoupper($this->faker->unique()->bothify('SKU-#####-??')),
            'price' => $price,
            'compare_at_price' => $price * 1.2, // السعر قبل الخصم
            'attributes' => json_encode([
                'color' => $this->faker->safeColorName(),
                'size' => $this->faker->randomElement(['S', 'M', 'L', 'XL', '40', '42', '44'])
            ]),
        ];
    }
}
