<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CouponFactory extends Factory
{
    public function definition(): array
    {
        $type = fake()->randomElement(['fixed', 'percentage']);
        $value = $type === 'fixed' ? fake()->randomFloat(2, 5, 200) : fake()->randomFloat(2, 5, 50);
        return [
            'code' => strtoupper(fake()->unique()->lexify('??????')),
            'type' => $type,
            'value' => $value,
            'start_date' => fake()->optional(0.5)->dateTimeBetween('-1 month', 'now'),
            'end_date' => fake()->optional(0.8)->dateTimeBetween('now', '+2 months'),
            'usage_limit' => fake()->optional(0.7)->numberBetween(10, 200),
            'used_count' => 0,
        ];
    }
}