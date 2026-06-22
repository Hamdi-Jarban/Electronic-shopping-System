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
            'created_at' => $this->randomDate(),
            'updated_at' => now(),
        ];
    }

    private function randomDate(): string
    {
        $dates = [now(), now()->subDay(), now()->subDays(2), now()->subWeek(), now()->subMonth(), fake()->dateTimeBetween('-1 year', 'now')];
        return fake()->randomElement($dates);
    }
}