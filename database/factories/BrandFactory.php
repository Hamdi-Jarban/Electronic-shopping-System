<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BrandFactory extends Factory
{
  public function definition(): array
  {
    $name = fake()->unique()->company();
    return [
      'name' => $name,
      'slug' => Str::slug($name),
      'logo_url' => fake()->optional(0.7)->imageUrl(200, 200, 'brand', true),
      'is_active' => fake()->boolean(80),
      'created_at' => $this->randomDate(),
      'updated_at' => now(),
    ];
  }

  private function randomDate(): string
  {
    $dates = [now(),
      now()->subDay(),
      now()->subDays(2),
      now()->subWeek(),
       now()->subMonth(),
      fake()->dateTimeBetween('-1 year', 'now')];
    return fake()->randomElement($dates);
  }
}