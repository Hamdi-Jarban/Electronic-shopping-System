<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrderReturnItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'order_return_id' => null,
            'variant_id' => null,
            'quantity' => fake()->numberBetween(1, 2),
            'condition' => fake()->randomElement(['good', 'opened', 'damaged', 'defective']),
        ];
    }
}