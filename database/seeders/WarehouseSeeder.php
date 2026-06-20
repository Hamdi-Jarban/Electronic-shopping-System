<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WarehouseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('warehouses')->insert([
            [
                'name' => 'المستودع المركزي - الرياض',
                'code' => 'WH-RUH-01',
                'address' => 'حي السلي, شارع هارون الرشيد',
                'city' => 'الرياض',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'مستودع المنطقة الغربية - جدة',
                'code' => 'WH-JED-02',
                'address' => 'المنطقة الصناعية الثالثة',
                'city' => 'جدة',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
