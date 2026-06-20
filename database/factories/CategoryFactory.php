<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
  public function definition(): array
  {
    $name = ucfirst($this->faker->unique()->words(2, true));

    return [
      'parent_id' => null,
      'name' => $name,
      'slug' => Str::slug($name),
      'is_active' => true,
    ];
  }
}