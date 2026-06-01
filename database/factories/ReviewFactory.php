<?php

namespace Database\Factories;

use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'user_id' => null,
            'product_id' => null,
            'rating' => fake()->numberBetween(1, 5),
            'comment_text' => fake()->optional(0.8)->sentence(10),
            'created_at' => fake()->dateTimeBetween('-6 months', 'now'),
        ];
    }
}
