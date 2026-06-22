<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrderHistoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'order_id' => null,
            'changed_by' => null,
            'old_status' => 'pending',
            'new_status' => 'processing',
            'comment' => fake()->optional()->sentence(),
            'created_at' => now(),
        ];
    }
}