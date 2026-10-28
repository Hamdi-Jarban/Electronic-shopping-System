<?php

namespace Tests\Feature;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarehouseInventory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Support\Str;
use Tests\TestCase;

class CartMergeStockTest extends TestCase
{
    use RefreshDatabase;

    public function test_merging_guest_cart_caps_combined_quantity_at_available_stock(): void
    {
        $user = User::factory()->create();
        [$variant, $warehouse] = $this->makeVariant('merge-one');
        WarehouseInventory::create([
            'warehouse_id' => $warehouse->id,
            'variant_id' => $variant->id,
            'physical_qty' => 5,
            'reserved_qty' => 0,
            'low_stock_threshold' => 1,
        ]);
        CartItem::create(['user_id' => $user->id, 'variant_id' => $variant->id, 'quantity' => 4]);
        $token = (string) Str::uuid();
        CartItem::create(['session_token' => $token, 'variant_id' => $variant->id, 'quantity' => 4]);

        [$addedVariant] = $this->makeVariant('merge-two');
        WarehouseInventory::create([
            'warehouse_id' => $warehouse->id,
            'variant_id' => $addedVariant->id,
            'physical_qty' => 3,
            'reserved_qty' => 0,
            'low_stock_threshold' => 1,
        ]);

        $response = $this->withoutMiddleware()->actingAs($user)
            ->withSession(['cart_session_token' => $token])
            ->postJson(route('cart.add'), ['variant_id' => $addedVariant->id, 'quantity' => 1]);

        $response->assertOk()->assertJsonPath('cart_merge.adjusted', 1);
        $this->assertDatabaseHas('cart_items', [
            'user_id' => $user->id,
            'variant_id' => $variant->id,
            'quantity' => 5,
        ]);
        $this->assertDatabaseMissing('cart_items', ['session_token' => $token]);
    }

    public function test_merging_cart_removes_existing_items_when_no_stock_is_available(): void
    {
        $user = User::factory()->create();
        [$variant, $warehouse] = $this->makeVariant('merge-empty');
        WarehouseInventory::create([
            'warehouse_id' => $warehouse->id,
            'variant_id' => $variant->id,
            'physical_qty' => 3,
            'reserved_qty' => 3,
            'low_stock_threshold' => 1,
        ]);
        CartItem::create(['user_id' => $user->id, 'variant_id' => $variant->id, 'quantity' => 1]);
        $token = (string) Str::uuid();
        CartItem::create(['session_token' => $token, 'variant_id' => $variant->id, 'quantity' => 2]);

        [$addedVariant] = $this->makeVariant('merge-available');
        WarehouseInventory::create([
            'warehouse_id' => $warehouse->id,
            'variant_id' => $addedVariant->id,
            'physical_qty' => 3,
            'reserved_qty' => 0,
            'low_stock_threshold' => 1,
        ]);

        $response = $this->withoutMiddleware()->actingAs($user)
            ->withSession(['cart_session_token' => $token])
            ->postJson(route('cart.add'), ['variant_id' => $addedVariant->id, 'quantity' => 1]);

        $response->assertOk()->assertJsonPath('cart_merge.skipped', 1);
        $this->assertDatabaseMissing('cart_items', ['variant_id' => $variant->id]);
    }

    public function test_checkout_records_the_exact_inventory_reservation_for_each_order_item(): void
    {
        [$variant, $warehouse] = $this->makeVariant('checkout-reservation');
        $inventory = WarehouseInventory::create([
            'warehouse_id' => $warehouse->id,
            'variant_id' => $variant->id,
            'physical_qty' => 7,
            'reserved_qty' => 1,
            'low_stock_threshold' => 1,
        ]);
        $token = (string) Str::uuid();
        CartItem::create([
            'session_token' => $token,
            'variant_id' => $variant->id,
            'quantity' => 3,
        ]);

        $this->withoutExceptionHandling();
        $response = $this->withoutMiddleware(ValidateCsrfToken::class)->withSession(['cart_session_token' => $token])
            ->postJson(route('orders.store'), [
                'customer_name' => 'Test customer',
                'customer_phone' => '0500000000',
                'shipping_address' => 'Test address',
            ]);

        $response->assertCreated();
        $orderItemId = \App\Models\OrderItem::query()->value('id');
        $this->assertDatabaseHas('order_inventory_reservations', [
            'order_item_id' => $orderItemId,
            'warehouse_inventory_id' => $inventory->id,
            'quantity' => 3,
            'status' => 'reserved',
        ]);
        $this->assertSame(4, (int) $inventory->fresh()->reserved_qty);
    }

    private function makeVariant(string $suffix): array
    {
        $product = Product::create([
            'name' => 'Product '.$suffix,
            'slug' => 'product-'.$suffix.'-'.Str::lower(Str::random(6)),
            'is_active' => true,
        ]);
        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'SKU-'.$suffix.'-'.Str::upper(Str::random(5)),
            'price' => 9.99,
            'attributes' => [],
        ]);
        $warehouse = Warehouse::firstOrCreate(
            ['code' => 'WH-MERGE'],
            ['name' => 'Merge test warehouse', 'city' => 'Test city', 'is_active' => true]
        );

        return [$variant, $warehouse];
    }
}
