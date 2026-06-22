<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CartItemFactory extends Factory
{
    public function definition(): array
    {
        $useUser = fake()->boolean(70);
        return [
            'user_id' => $useUser ? null : null,
            'session_token' => !$useUser ? Str::random(40) : null,
            'variant_id' => null,
            'quantity' => fake()->numberBetween(1, 5),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}