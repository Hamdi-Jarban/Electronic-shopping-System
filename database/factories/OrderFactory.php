<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => null,
            'coupon_id' => null,
            'order_number' => 'ORD-' . strtoupper(fake()->unique()->lexify('??????')),
            'status' => 'pending',
            'total_amount' => 0,
            'discount_amount' => 0,
            'net_amount' => 0,
            'created_at' => fake()->dateTimeBetween('-6 months', 'now'),
        ];
    }
}