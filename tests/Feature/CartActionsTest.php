<?php

namespace Tests\Feature;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarehouseInventory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CartActionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_update_cart_quantity_without_exceeding_available_stock(): void
    {
        [$user, $variant, $inventory, $item] = $this->makeCartItem(4, 2);
        $response = $this->actingAs($user)->patchJson(route('cart.update', $item), ['quantity' => 3]);

        $response->assertOk()->assertJsonPath('quantity', 3)->assertJsonPath('cart_count', 3);
        $this->assertSame(3, (int) $item->fresh()->quantity);
    }

    public function test_customer_cannot_increase_cart_quantity_above_current_stock(): void
    {
        [$user, $variant, $inventory, $item] = $this->makeCartItem(2, 1);
        $response = $this->actingAs($user)->patchJson(route('cart.update', $item), ['quantity' => 3]);

        $response->assertUnprocessable()->assertJsonPath('success', false);
        $this->assertSame(2, (int) $item->fresh()->quantity);
    }

    public function test_customer_can_remove_an_unavailable_cart_item(): void
    {
        [$user, $variant, $inventory, $item] = $this->makeCartItem(0, 2);
        $response = $this->actingAs($user)->deleteJson(route('cart.remove', $item));

        $response->assertOk()->assertJsonPath('cart_count', 0);
        $this->assertDatabaseMissing('cart_items', ['id' => $item->id]);
    }

    private function makeCartItem(int $available, int $quantity): array
    {
        $user = User::factory()->create();
        $product = Product::create([
            'name' => 'منتج سلة تجريبي',
            'slug' => 'cart-' . Str::lower(Str::random(8)),
            'is_active' => true,
        ]);
        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'SKU-' . Str::upper(Str::random(8)),
            'price' => 12.50,
            'attributes' => [],
        ]);
        $warehouse = Warehouse::create([
            'name' => 'مستودع السلة',
            'code' => 'WH-' . Str::upper(Str::random(8)),
            'city' => 'الرياض',
            'is_active' => true,
        ]);
        $inventory = WarehouseInventory::create([
            'warehouse_id' => $warehouse->id,
            'variant_id' => $variant->id,
            'physical_qty' => $available,
            'reserved_qty' => 0,
            'low_stock_threshold' => 1,
        ]);
        $item = CartItem::create([
            'user_id' => $user->id,
            'variant_id' => $variant->id,
            'quantity' => $quantity,
        ]);

        return [$user, $variant, $inventory, $item];
    }
}
