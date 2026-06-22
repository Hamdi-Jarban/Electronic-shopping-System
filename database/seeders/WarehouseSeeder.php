<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WarehouseSeeder extends Seeder
{
    public function run(): void
    {
        fake()->locale('ar_SA');

        $cities = ['الرياض', 'جدة', 'الدمام', 'بريدة', 'أبها', 'تبوك', 'حائل', 'نجران', 'جازان', 'المدينة المنورة'];
        $districts = ['الصناعية', 'المركزية', 'الجنوبية', 'الشمالية', 'الشرقية', 'الغربية', 'الجديدة', 'المطار'];

        $warehouses = [];
        for ($i = 0; $i < 10; $i++) {
            $city     = $cities[$i];
            $district = fake()->randomElement($districts);

            $warehouses[] = [
                'name'       => 'مستودع ' . $city . ' - ' . $district,
                'code'       => 'WH-' . strtoupper(fake()->bothify('??###')),
                'address'    => 'المنطقة ' . $district . '، ' . $city . '، المملكة العربية السعودية',
                'city'       => $city,
                'is_active'  => true,
                'created_at' => fake()->dateTimeBetween('-2 years', 'now')->format('Y-m-d H:i:s'),
                'updated_at' => now()->toDateTimeString(),
            ];
        }

        DB::table('warehouses')->insert($warehouses);
        $this->command->info('✅ 10 مستودعات في مدن المملكة تم إنشاؤها.');
    }
}