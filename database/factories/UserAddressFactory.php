<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserAddressFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => null,
            'address_line1' => fake()->streetAddress(),
            'address_line2' => fake()->optional()->secondaryAddress(),
            'city' => fake()->city(),
            'country' => 'المملكة العربية السعودية',
            'postal_code' => fake()->optional()->postcode(),
            'is_default' => false,
        ];
    }
}