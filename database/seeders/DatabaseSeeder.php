<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;

class DatabaseSeeder extends Seeder
{

  public function run(): void
  {
    // 1. تشغيل الـ Seeders الثابتة (المرحلة الأولى)
    $this->call([
    RolePermissionSeeder::class,
    WarehouseSeeder::class,
    AdminUserSeeder::class,
    ]);

    // 2. تشغيل الـ Factories لتوليد بيانات وهمية (المرحلة الثانية)
    // سنقوم بإنشاء 10 علامات تجارية و 10 أقسام
    \App\Models\Brand::factory(10)->create();
    \App\Models\Category::factory(10)->create();

    // إنشاء 30 منتجاً
    \App\Models\Product::factory(30)->create();

    // إنشاء 60 متغيراً وتوزيعهم عشوائياً على المنتجات
    \App\Models\ProductVariant::factory(60)->create();

    // 3. تغذية المخزون عشوائياً للمستودعات لكي لا تصبح الكميات صفرية
    $variants = \DB::table('product_variants')->get();
    $warehouses = \DB::table('warehouses')->get();

    foreach ($warehouses as $warehouse) {
      foreach ($variants as $variant) {
        // نربط كل متغير بكل مستودع بكمية عشوائية
        \DB::table('warehouse_inventory')->insert([
        'warehouse_id' => $warehouse->id,
        'variant_id' => $variant->id,
        'physical_qty' => rand(20, 150),
        'reserved_qty' => rand(0, 5),
        'low_stock_threshold' => 10,
        'updated_at' => now(),
        ]);
      }
    }
  }
}