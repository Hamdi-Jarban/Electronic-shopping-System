<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ShipmentFactory extends Factory
{
    public function definition(): array
    {
        $carriers = ['ارامكس', 'دي اتش ال', 'فاستلو', 'البريد السعودي'];
        return [
            'order_id' => null,
            'warehouse_id' => null,
            'carrier_name' => fake()->randomElement($carriers),
            'tracking_number' => strtoupper(fake()->unique()->lexify('TRK??????')),
            'status' => fake()->randomElement(['pending', 'pickup', 'in_transit', 'out_for_delivery', 'delivered']),
            'shipping_cost' => fake()->randomFloat(2, 5, 50),
            'shipping_price' => fake()->randomFloat(2, 10, 70),
            'shipped_at' => fake()->optional(0.7)->dateTimeBetween('-1 week', 'now'),
            'delivered_at' => fake()->optional(0.5)->dateTimeBetween('-3 days', 'now'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}