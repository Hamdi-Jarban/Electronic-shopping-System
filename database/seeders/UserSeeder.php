<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Customer;
use App\Models\Admin;
use App\Models\SupportStaff;
use App\Models\InventoryManager;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 4 مستخدمين للاختبار السريع
        $testUsers = [
            [
                'email' => 'admin@supermarket.com',
                'full_name' => 'مدير النظام',
                'role' => 'admin',
                'department' => 'تقنية المعلومات',
                'permissions' => 'full',
                'salary' => 12000,
            ],
            [
                'email' => 'customer@supermarket.com',
                'full_name' => 'عميل تجريبي',
                'role' => 'customer',
                'date_of_birth' => '1990-01-01',
                'address' => 'الرياض، حي النخيل، شارع الملك فهد',
            ],
            [
                'email' => 'support@supermarket.com',
                'full_name' => 'موظف دعم فني',
                'role' => 'support',
                'department' => 'الدعم الفني',
                'salary' => 7000,
            ],
            [
                'email' => 'inventory@supermarket.com',
                'full_name' => 'مدير المخزون',
                'role' => 'inventory',
                'salary' => 9000,
            ],
        ];

        foreach ($testUsers as $data) {
            $user = User::create([
                'email' => $data['email'],
                'password' => Hash::make('password'), // تعديل الحقل هنا إلى password
                'full_name' => $data['full_name'],
                'phone' => '05' . rand(10000000, 99999999),
                'role' => $data['role'],
                'created_at' => now(),
            ]);

            match ($data['role']) {
                'customer' => Customer::create([
                    'user_id' => $user->user_id,
                    'date_of_birth' => $data['date_of_birth'],
                    'default_address' => $data['address'],
                ]),
                'admin' => Admin::create([
                    'user_id' => $user->user_id,
                    'department' => $data['department'],
                    'permissions' => $data['permissions'],
                    'salary' => $data['salary'],
                ]),
                'support' => SupportStaff::create([
                    'user_id' => $user->user_id,
                    'department' => $data['department'],
                    'is_online' => true,
                    'salary' => $data['salary'],
                ]),
                'inventory' => InventoryManager::create([
                    'user_id' => $user->user_id,
                    'warehouse_id' => 1,
                    'salary' => $data['salary'],
                ]),
                default => null
            };
        }

        // 50 عميل عشوائي
        for ($i = 1; $i <= 50; $i++) {
            $user = User::create([
                'email' => 'customer' . $i . '@example.com',
                'password' => Hash::make('password'), // تعديل الحقل هنا إلى password
                'full_name' => 'عميل ' . $i,
                'phone' => '05' . rand(10000000, 99999999),
                'role' => 'customer',
                'created_at' => now()->subDays(rand(1, 365)),
            ]);

            Customer::create([
                'user_id' => $user->user_id,
                'date_of_birth' => now()->subYears(rand(18, 60))->format('Y-m-d'),
                'default_address' => 'عنوان العميل ' . $i,
                'preferences_json' => json_encode(['language' => 'ar', 'currency' => 'SAR']),
            ]);
        }

        // 5 مشرفين
        for ($i = 1; $i <= 5; $i++) {
            $user = User::create([
                'email' => 'admin' . $i . '@example.com',
                'password' => Hash::make('password'), // تعديل الحقل هنا إلى password
                'full_name' => 'مشرف ' . $i,
                'phone' => '05' . rand(10000000, 99999999),
                'role' => 'admin',
                'created_at' => now()->subDays(rand(1, 365)),
            ]);

            Admin::create([
                'user_id' => $user->user_id,
                'department' => ['IT', 'Sales', 'Finance', 'Marketing', 'HR'][$i - 1],
                'permissions' => $i === 1 ? 'full' : 'limited',
                'salary' => rand(5000, 15000),
            ]);
        }

        // 5 موظفي دعم
        for ($i = 1; $i <= 5; $i++) {
            $user = User::create([
                'email' => 'support' . $i . '@example.com',
                'password' => Hash::make('password'), // تعديل الحقل هنا إلى password
                'full_name' => 'موظف دعم ' . $i,
                'phone' => '05' . rand(10000000, 99999999),
                'role' => 'support',
                'created_at' => now()->subDays(rand(1, 365)),
            ]);

            SupportStaff::create([
                'user_id' => $user->user_id,
                'department' => 'الدعم الفني',
                'is_online' => rand(0, 1),
                'salary' => rand(4000, 8000),
            ]);
        }

        $this->command->info('✓ تم إنشاء ' . User::count() . ' مستخدم');
        $this->command->info('  - ' . Customer::count() . ' عميل');
        $this->command->info('  - ' . Admin::count() . ' مشرف');
        $this->command->info('  - ' . SupportStaff::count() . ' موظف دعم');
        $this->command->info('  - ' . InventoryManager::count() . ' مدير مخزون');
    }
}
