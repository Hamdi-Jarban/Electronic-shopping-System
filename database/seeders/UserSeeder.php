<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        fake()->locale('ar_SA');

        $this->command->info('👤 إنشاء 500 مستخدم...');

        // قوائم عربية
        $cities = ['الرياض', 'جدة', 'مكة المكرمة', 'المدينة المنورة', 'الدمام', 'الخبر', 'الظهران', 'بريدة', 'تبوك', 'أبها', 'حائل', 'الطائف', 'نجران', 'جازان', 'ينبع', 'القصيم', 'الأحساء', 'القطيف', 'خميس مشيط', 'الجوف'];
        $streets = ['الملك فهد', 'العليا', 'التحلية', 'الأمير سلطان', 'أبو بكر الصديق', 'عثمان بن عفان', 'عمر بن الخطاب', 'السلام', 'المطار', 'الجامعة', 'الملك عبد العزيز', 'الملك سلمان', 'الملك عبدالله', 'الملك فيصل', 'الثمامة'];
        $districts = ['الروضة', 'الربوة', 'الملز', 'السليمانية', 'النزهة', 'الورود', 'العارض', 'الياسمين', 'الربيع', 'القدس', 'الصحافة', 'الملقا', 'الغدير', 'الريان', 'إشبيليا', 'المروج', 'النخيل', 'الفيحاء', 'الشاطئ', 'السلام'];

        // إنشاء 500 مستخدم دفعة واحدة
        $usersData = [];
        for ($i = 0; $i < 500; $i++) {
            $firstName  = fake()->firstNameMale();
            $middleName = fake()->firstNameMale();
            $lastName   = fake()->lastName();

            $usersData[] = [
                'name'       => $firstName . ' ' . $middleName . ' ' . $lastName,
                'email'      => strtolower($firstName . '.' . $lastName . fake()->numberBetween(10, 999) . '@' . fake()->randomElement(['gmail.com', 'hotmail.com', 'outlook.com', 'yahoo.com'])),
                'password'   => Hash::make('password'),
                'phone'      => '05' . fake()->numberBetween(10000000, 99999999),
                'created_at' => fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s'),
                'updated_at' => now()->toDateTimeString(),
            ];
        }

        DB::table('users')->insert($usersData);
        $this->command->info('✅ 500 مستخدم تم إنشاؤهم.');

        // الروابط
        $userIds    = DB::table('users')->pluck('id')->toArray();
        $roleIds    = DB::table('roles')->pluck('id')->toArray();
        $warehouseIds = DB::table('warehouses')->pluck('id')->toArray();

        $roleUser      = [];
        $warehouseUser = [];
        $addresses     = [];

        $this->command->info('🔗 ربط الأدوار والمستودعات والعناوين...');

        foreach ($userIds as $userId) {
            // أدوار
            $assignedRoles = (array) array_rand(array_flip($roleIds), rand(1, min(2, count($roleIds))));
            foreach ($assignedRoles as $roleId) {
                $roleUser[] = ['user_id' => $userId, 'role_id' => $roleId];
            }

            // مستودعات
            if (fake()->boolean(70) && !empty($warehouseIds)) {
                $assignedWh = (array) array_rand(array_flip($warehouseIds), rand(1, min(2, count($warehouseIds))));
                foreach ($assignedWh as $whId) {
                    $warehouseUser[] = ['user_id' => $userId, 'warehouse_id' => $whId];
                }
            }

            // عنوان افتراضي أساسي
            $addresses[] = [
                'user_id'       => $userId,
                'address_line1' => 'شارع ' . fake()->randomElement($streets) . '، حي ' . fake()->randomElement($districts),
                'address_line2' => fake()->boolean(30) ? 'مبنى رقم ' . fake()->numberBetween(1, 50) . '، شقة ' . fake()->numberBetween(1, 20) : null,
                'city'          => fake()->randomElement($cities),
                'country'       => 'المملكة العربية السعودية',
                'postal_code'   => fake()->numberBetween(10000, 99999),
                'is_default'    => true,
            ];

            // عنوان إضافي (40% احتمال)
            if (fake()->boolean(40)) {
                $addresses[] = [
                    'user_id'       => $userId,
                    'address_line1' => 'شارع ' . fake()->randomElement($streets) . '، حي ' . fake()->randomElement($districts),
                    'address_line2' => fake()->boolean(20) ? 'ص.ب ' . fake()->numberBetween(1000, 9999) : null,
                    'city'          => fake()->randomElement($cities),
                    'country'       => 'المملكة العربية السعودية',
                    'postal_code'   => fake()->numberBetween(10000, 99999),
                    'is_default'    => false,
                ];
            }
        }

        foreach (array_chunk($roleUser, 1000) as $chunk) {
            DB::table('role_user')->insert($chunk);
        }
        if (!empty($warehouseUser)) {
            foreach (array_chunk($warehouseUser, 1000) as $chunk) {
                DB::table('warehouse_user')->insert($chunk);
            }
        }
        foreach (array_chunk($addresses, 500) as $chunk) {
            DB::table('user_addresses')->insert($chunk);
        }

        $this->command->info('✅ تم ربط الأدوار والمستودعات والعناوين.');
    }
}