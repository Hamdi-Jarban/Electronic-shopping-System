<?php

namespace Database\Factories;

use App\Models\Inventory;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryFactory extends Factory
{
    protected $model = Inventory::class;

    public function definition(): array
    {
        return [
            'variant_id' => null,
            'warehouse_id' => null,
            'quantity_in_stock' => fake()->numberBetween(0, 500),
            'reorder_level' => 10,
            'reorder_quantity' => 50,
            'last_updated' => fake()->dateTimeBetween('-3 months', 'now'),
        ];
    }
}
