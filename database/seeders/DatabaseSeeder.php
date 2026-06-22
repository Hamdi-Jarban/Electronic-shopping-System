<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        fake()->locale('ar_SA');


        $this->command->info('🚀 بدء إدخال البيانات باللغة العربية...');
        $this->command->info('⏳ هذه العملية قد تستغرق بضع دقائق...');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->call([
            RolePermissionSeeder::class,
            WarehouseSeeder::class,
            BrandSeeder::class,
            CategorySeeder::class,
            CouponSeeder::class,
            UserSeeder::class,
            ProductSeeder::class,
            CartSeeder::class,
            OrderSeeder::class,
            InventoryMovementSeeder::class,
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('✅ تم إدخال جميع البيانات بنجاح!');
        $this->command->info('📊 الإحصائيات:');
        $this->command->info('   - المستخدمون: ' . DB::table('users')->count());
        $this->command->info('   - المنتجات: ' . DB::table('products')->count());
        $this->command->info('   - الطلبات: ' . DB::table('orders')->count());
        $this->command->info('   - عناصر السلة: ' . DB::table('cart_items')->count());
        $this->command->info('   - المراجعات: ' . DB::table('product_reviews')->count());
    }
}