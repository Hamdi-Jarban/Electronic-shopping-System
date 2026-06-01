<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $this->command->info('🚀 بدء إضافة البيانات التجريبية...');
        $this->command->info('');

        // المرحلة 1: بيانات أساسية
        $this->call(WarehouseSeeder::class);
        $this->call(SupplierSeeder::class);
        $this->call(BrandAndCategorySeeder::class);
        $this->command->info('');

        // المرحلة 2: المستخدمين
        $this->call(UserSeeder::class);
        $this->command->info('');

        // المرحلة 3: المنتجات
        $this->call(ProductSeeder::class);
        $this->command->info('');

        // المرحلة 4: المخزون
        $this->call(InventorySeeder::class);
        $this->command->info('');

        // المرحلة 5: الطلبات
        $this->call(OrderSeeder::class);
        $this->command->info('');

        // المرحلة 6: التقييمات والسلال
        $this->call(ReviewSeeder::class);
        $this->call(CartSeeder::class);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->command->info('');
        $this->command->info('✅ تمت إضافة جميع البيانات بنجاح!');
        $this->command->info('');
        $this->command->info('👤 حسابات الاختبار (كلمة المرور: password):');
        $this->command->info('   admin@supermarket.com     - مدير النظام');
        $this->command->info('   customer@supermarket.com  - عميل');
        $this->command->info('   support@supermarket.com   - موظف دعم');
        $this->command->info('   inventory@supermarket.com - مدير مخزون');
    }
}
