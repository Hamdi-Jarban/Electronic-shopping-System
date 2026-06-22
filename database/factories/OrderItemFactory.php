<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'order_id' => null,
            'variant_id' => null,
            'quantity' => fake()->numberBetween(1, 3),
            'price' => 0, // يُحسب لاحقاً
        ];
    }
}