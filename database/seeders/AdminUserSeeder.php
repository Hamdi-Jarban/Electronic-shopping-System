<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
  public function run(): void
  {
    // إنشاء المستخدم
    $userId = DB::table('users')->insertGetId([
    'name' => 'Hamdi Soft Admin',
    'email' => 'admin@hamdisoft.com',
    'password' => Hash::make('password123'), // كلمة المرور الافتراضية
    'phone' => '+966500000000',
    'created_at' => now(),
    'updated_at' => now(),
    ]);

    // جلب رقم دور المدير العام
    $adminRole = DB::table('roles')->where('name', 'super_admin')->first();

    if ($adminRole) {
      // ربط المستخدم بدور المدير
      DB::table('role_user')->insert([
      'user_id' => $userId,
      'role_id' => $adminRole->id,
      ]);
    }
  }
}