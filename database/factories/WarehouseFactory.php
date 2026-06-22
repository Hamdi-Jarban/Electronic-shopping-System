<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class WarehouseFactory extends Factory
{
    public function definition(): array
    {
        $city = fake()->city();
        return [
            'name' => 'مستودع ' . $city,
            'code' => strtoupper(fake()->unique()->lexify('WH-???')),
            'address' => fake()->optional(0.8)->address(),
            'city' => $city,
            'is_active' => fake()->boolean(90),
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