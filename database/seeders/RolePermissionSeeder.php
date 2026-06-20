<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. إدخال الأدوار
        $adminRoleId = DB::table('roles')->insertGetId([
            'name' => 'super_admin',
            'display_name' => 'مدير النظام',
            'description' => 'يمتلك كامل الصلاحيات في المتجر',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $managerRoleId = DB::table('roles')->insertGetId([
            'name' => 'warehouse_manager',
            'display_name' => 'مدير مستودع',
            'description' => 'إدارة المخزون والشحن فقط',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. إدخال الصلاحيات
        $permissions = [
            ['name' => 'manage_settings', 'display_name' => 'إدارة الإعدادات', 'group_name' => 'system'],
            ['name' => 'manage_products', 'display_name' => 'إدارة المنتجات', 'group_name' => 'catalog'],
            ['name' => 'manage_orders', 'display_name' => 'إدارة الطلبات', 'group_name' => 'sales'],
            ['name' => 'manage_inventory', 'display_name' => 'إدارة المخزون', 'group_name' => 'logistics'],
        ];

        $permissionIds = [];
        foreach ($permissions as $permission) {
            $permissionIds[] = DB::table('permissions')->insertGetId(array_merge($permission, ['created_at' => now()]));
        }

        // 3. ربط الصلاحيات بالأدوار (كل الصلاحيات للمدير العام)
        foreach ($permissionIds as $id) {
            DB::table('permission_role')->insert([
                'role_id' => $adminRoleId,
                'permission_id' => $id
            ]);
        }

        // صلاحية المخزون فقط لمدير المستودع
        DB::table('permission_role')->insert([
            'role_id' => $managerRoleId,
            'permission_id' => $permissionIds[3] // manage_inventory
        ]);
    }
}
