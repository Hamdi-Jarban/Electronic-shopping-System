<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  public function run(): void
  {
    $this->command->info('بدء عملية إدخال البيانات...');

    $this->call([
    RolePermissionSeeder::class,
    WarehouseSeeder::class,      // قبل UserSeeder (لربط المستخدمين)
    BrandSeeder::class,
    CategorySeeder::class,
    CouponSeeder::class,
    UserSeeder::class,           // بعد WarehouseSeeder
    ProductSeeder::class,        // بعد Brand, Category, User
    CartSeeder::class,           // بعد ProductVariant
    OrderSeeder::class,          // بعد User, Coupon, Warehouse, ProductVariant
    InventoryMovementSeeder::class, // بعد Warehouse, ProductVariant, User
    ]);

    $this->command->info('تم إدخال جميع البيانات بنجاح! 🎉');
  }
}