<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Warehouse;
use Database\Seeders\WarehouseInventorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class WarehouseInventorySeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_missing_variants_receive_idempotent_demo_inventory_and_opening_movement(): void
    {
        $product = Product::create([
            'name' => 'منتج تجريبي',
            'slug' => 'demo-' . Str::lower(Str::random(8)),
            'is_active' => true,
        ]);
        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'SKU-' . Str::upper(Str::random(8)),
            'price' => 15,
            'attributes' => [],
        ]);
        Warehouse::create([
            'name' => 'مستودع تجريبي',
            'code' => 'WH-' . Str::upper(Str::random(8)),
            'city' => 'الرياض',
            'is_active' => true,
        ]);

        $seeder = new WarehouseInventorySeeder();
        $seeder->run();
        $seeder->run();

        $this->assertDatabaseHas('warehouse_inventory', [
            'variant_id' => $variant->id,
            'physical_qty' => 10,
            'reserved_qty' => 0,
        ]);
        $this->assertDatabaseCount('warehouse_inventory', 1);
        $this->assertDatabaseHas('inventory_movements', [
            'variant_id' => $variant->id,
            'quantity' => 10,
            'type' => 'adjustment',
            'reference_type' => 'seed_opening_balance',
        ]);
        $this->assertDatabaseCount('inventory_movements', 1);
    }
}
