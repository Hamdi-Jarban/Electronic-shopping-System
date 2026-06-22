<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrderReturnFactory extends Factory
{
    public function definition(): array
    {
        return [
            'order_id' => null,
            'user_id' => null,
            'return_number' => 'RET-' . strtoupper(fake()->unique()->lexify('??????')),
            'status' => fake()->randomElement(['requested', 'approved', 'received', 'refunded']),
            'refund_amount' => 0, // يحسب لاحقاً
            'reason' => fake()->optional()->sentence(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}