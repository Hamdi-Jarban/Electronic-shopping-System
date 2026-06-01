<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warehouse;

class WarehouseSeeder extends Seeder
{
    public function run(): void
    {
        Warehouse::create(['name' => 'المستودع الرئيسي', 'location' => 'الرياض']);
        Warehouse::create(['name' => 'مستودع الشمال', 'location' => 'جدة']);
        Warehouse::create(['name' => 'مستودع الجنوب', 'location' => 'الدمام']);
        Warehouse::create(['name' => 'مستودع التوزيع', 'location' => 'مكة']);
        Warehouse::create(['name' => 'مستودع مركزي', 'location' => 'المدينة']);
        Warehouse::create(['name' => 'مستودع شرق', 'location' => 'الدمام']);
        Warehouse::create(['name' => 'مستودع غرب', 'location' => 'جدة']);
        Warehouse::create(['name' => 'مستودع احتياطي', 'location' => 'القصيم']);

        $this->command->info('✓ تم إنشاء ' . Warehouse::count() . ' مستودع');
    }
}
