<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {fake()->locale('ar_SA');
        $roles = [
            ['name' => 'admin', 'display_name' => 'مدير النظام', 'description' => 'صلاحيات كاملة', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'manager', 'display_name' => 'مدير', 'description' => 'إدارة الفرق', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'operator', 'display_name' => 'مشغل', 'description' => 'إدخال بيانات', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'customer', 'display_name' => 'عميل', 'description' => 'مستخدم عادي', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('roles')->insert($roles);

        $permissions = [];
        $groups = ['users', 'products', 'orders', 'settings'];
        foreach ($groups as $group) {
            for ($i = 1; $i <= 5; $i++) {
                $name = "$group.action$i";
                $permissions[] = [
                    'name' => $name,
                    'display_name' => "صلاحية $group - $i",
                    'group_name' => $group,
                    'created_at' => now(),
                ];
            }
        }
        DB::table('permissions')->insert($permissions);

        // ربط الأدوار بالصلاحيات (اختياري)
        $roleIds = DB::table('roles')->pluck('id');
        $permIds = DB::table('permissions')->pluck('id');
        $pivot = [];
        foreach ($roleIds as $roleId) {
            $randomPerms = collect($permIds)->random(rand(3, 10));
            foreach ($randomPerms as $permId) {
                $pivot[] = ['role_id' => $roleId, 'permission_id' => $permId];
            }
        }
        DB::table('permission_role')->insert($pivot);
    }
}