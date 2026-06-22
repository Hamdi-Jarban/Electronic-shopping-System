<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('بدء إنشاء المستخدمين...');

        // ✅ 1. إدخال 500 مستخدم دفعة واحدة
        $totalUsers = 500;
        $usersData = [];

        for ($i = 0; $i < $totalUsers; $i++) {
            $usersData[] = [
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'password' => Hash::make('password'),
                'phone' => fake()->optional(0.9)->phoneNumber(),
                'created_at' => $this->randomDate(),
                'updated_at' => now()->toDateTimeString(),
            ];
        }

        DB::table('users')->insert($usersData);
        $this->command->info('تم إدخال 500 مستخدم.');

        // ✅ 2. جلب المعرفات مرة واحدة فقط
        $userIds = DB::table('users')->pluck('id')->toArray();
        $roleIds = DB::table('roles')->pluck('id')->toArray();
        $warehouseIds = DB::table('warehouses')->pluck('id')->toArray();

        // ✅ 3. تجهيز جميع الروابط في مصفوفات
        $roleUser = [];
        $warehouseUser = [];
        $addresses = [];

        $this->command->info('تجهيز الأدوار والمستودعات والعناوين...');

        foreach ($userIds as $userId) {
            // 1-2 دور عشوائي
            $assignedRoles = (array) array_rand(array_flip($roleIds), rand(1, min(2, count($roleIds))));
            foreach ($assignedRoles as $roleId) {
                $roleUser[] = [
                    'user_id' => $userId,
                    'role_id' => $roleId,
                ];
            }

            // 70% احتمال ربطه بمستودع
            if (fake()->boolean(70) && !empty($warehouseIds)) {
                $assignedWh = (array) array_rand(array_flip($warehouseIds), rand(1, min(2, count($warehouseIds))));
                foreach ($assignedWh as $whId) {
                    $warehouseUser[] = [
                        'user_id' => $userId,
                        'warehouse_id' => $whId,
                    ];
                }
            }

            // عنوان افتراضي أساسي لكل مستخدم
            $addresses[] = [
                'user_id' => $userId,
                'address_line1' => fake()->streetAddress(),
                'address_line2' => fake()->optional(0.3)->secondaryAddress(),
                'city' => fake()->city(),
                'country' => 'المملكة العربية السعودية',
                'postal_code' => fake()->optional(0.7)->postcode(),
                'is_default' => true,
            ];

            // 40% عنوان إضافي
            if (fake()->boolean(40)) {
                $addresses[] = [
                    'user_id' => $userId,
                    'address_line1' => fake()->streetAddress(),
                    'address_line2' => fake()->optional(0.3)->secondaryAddress(),
                    'city' => fake()->city(),
                    'country' => 'المملكة العربية السعودية',
                    'postal_code' => fake()->optional(0.7)->postcode(),
                    'is_default' => false,
                ];
            }
        }

        // ✅ 4. إدخال جميع الروابط دفعة واحدة
        $this->command->info('إدخال الأدوار...');
        foreach (array_chunk($roleUser, 1000) as $chunk) {
            DB::table('role_user')->insert($chunk);
        }

        $this->command->info('إدخال المستودعات...');
        if (!empty($warehouseUser)) {
            foreach (array_chunk($warehouseUser, 1000) as $chunk) {
                DB::table('warehouse_user')->insert($chunk);
            }
        }

        $this->command->info('إدخال العناوين...');
        foreach (array_chunk($addresses, 500) as $chunk) {
            DB::table('user_addresses')->insert($chunk);
        }

        $this->command->info('✅ تم إنشاء المستخدمين وجميع الارتباطات بنجاح!');
    }

    private function randomDate(): string
    {
        $dates = [
            now()->toDateTimeString(),
            now()->subDay()->toDateTimeString(),
            now()->subDays(2)->toDateTimeString(),
            now()->subWeek()->toDateTimeString(),
            now()->subMonth()->toDateTimeString(),
            fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s'),
        ];

        return fake()->randomElement($dates);
    }
}