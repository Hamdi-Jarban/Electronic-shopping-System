<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductReviewFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id' => null,
            'user_id' => null,
            'rating' => fake()->numberBetween(1, 5),
            'comment' => fake()->optional()->realText(100),
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}