<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryMovementFactory extends Factory
{
    public function definition(): array
    {
        return [
            'warehouse_id' => null,
            'variant_id' => null,
            'user_id' => null,
            'quantity' => fake()->numberBetween(-50, 50),
            'type' => fake()->randomElement(['inbound', 'outbound', 'adjustment', 'return', 'allocation']),
            'reference_type' => 'manual',
            'reference_id' => null,
            'reason' => fake()->optional()->sentence(),
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}