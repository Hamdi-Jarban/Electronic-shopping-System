<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Role;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdminProductInventoryIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_created_product_is_saved_with_stock_shown_in_store_and_can_be_added_to_cart(): void
    {
        Storage::fake('public');
        $role = Role::create(['name' => 'admin', 'display_name' => 'Administrator']);
        $admin = User::factory()->create();
        $admin->roles()->attach($role);
        $brand = Brand::create([
            'name' => 'علامة اختبار',
            'slug' => 'test-brand-' . Str::lower(Str::random(6)),
            'is_active' => true,
        ]);
        $category = Category::create([
            'name' => 'تصنيف اختبار',
            'slug' => 'test-category-' . Str::lower(Str::random(6)),
            'is_active' => true,
        ]);
        $warehouse = Warehouse::create([
            'name' => 'مستودع الاختبار',
            'code' => 'WH-' . Str::upper(Str::random(8)),
            'city' => 'الرياض',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.products.store'), [
            'name' => 'سماعة اختبار للمخزون',
            'description' => 'منتج لاختبار ربط قاعدة البيانات بالمخزون.',
            'summary' => 'سماعة مع رصيد اختبار.',
            'brand_id' => $brand->id,
            'category_ids' => [$category->id],
            'images' => [[
                'file' => UploadedFile::fake()->createWithContent(
                    'test-product.png',
                    base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/l1sAAAAASUVORK5CYII=')
                ),
                'is_featured' => '1',
                'sort_order' => '0',
            ]],
            'variants' => [[
                'price' => '149.50',
                'compare_at_price' => '179.00',
                'warehouse_id' => $warehouse->id,
                'physical_qty' => '23',
                'low_stock_threshold' => '3',
                'attributes' => ['اللون' => 'أسود'],
            ]],
        ]);

        $response->assertCreated()->assertJsonPath('success', true);
        $productId = $response->json('data.id');
        $this->assertDatabaseHas('products', ['id' => $productId, 'name' => 'سماعة اختبار للمخزون']);
        $this->assertDatabaseHas('warehouse_inventory', [
            'warehouse_id' => $warehouse->id,
            'physical_qty' => 23,
            'reserved_qty' => 0,
        ]);

        $variantId = \App\Models\ProductVariant::where('product_id', $productId)->value('id');
        $this->get(route('index'))
            ->assertOk()
            ->assertSee('سماعة اختبار للمخزون')
            ->assertSee('متوفر: 23 قطعة');

        $this->postJson(route('cart.add'), ['variant_id' => $variantId, 'quantity' => 2])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('cart_count', 2);
    }
}
