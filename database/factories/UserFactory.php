<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'phone' => fake()->optional(0.9)->phoneNumber(),
            'email_verified_at' => now(),
            'created_at' => $this->randomDate(),
            'updated_at' => now(),
        ];
    }

    private function randomDate(): string
    {
        return fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s');
    }

    public function unverified(): static
    {
        return $this->state(fn () => ['email_verified_at' => null]);
    }
}
