<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        fake()->locale('ar_SA');

        $mainCategories = [
            'ملابس', 'أحذية', 'حقائب', 'إكسسوارات', 'عطور',
            'عناية بالبشرة', 'مستحضرات تجميل', 'ساعات', 'نظارات', 'مجوهرات',
            'إلكترونيات', 'أجهزة منزلية', 'أثاث', 'مستلزمات رياضية', 'كتب',
            'ألعاب', 'مواد غذائية', 'مشروبات', 'هدايا', 'منتجات فاخرة',
        ];

        // فئات رئيسية
        $parents = [];
        foreach ($mainCategories as $name) {
            $parents[] = [
                'parent_id'  => null,
                'name'       => $name,
                'slug'       => Str::slug($name) . '-' . fake()->numberBetween(10, 99),
                'is_active'  => true,
                'created_at' => now()->toDateTimeString(),
                'updated_at' => now()->toDateTimeString(),
            ];
        }

        DB::table('categories')->insert($parents);
        $parentIds = DB::table('categories')->pluck('id')->toArray();

        // فئات فرعية
        $subCategories = [
            'رجالي', 'نسائي', 'أطفال', 'رياضي', 'كلاسيكي',
            'عصري', 'فاخر', 'يومي', 'موسمي', 'رسمي',
            'صيفي', 'شتوي', 'ربيعي', 'خريفي', 'كاجوال',
        ];

        $children = [];
        foreach ($parentIds as $parentId) {
            $count = rand(3, 5);
            for ($j = 0; $j < $count; $j++) {
                $subName = fake()->randomElement($subCategories) . ' ' . fake()->randomElement($mainCategories);
                $children[] = [
                    'parent_id'  => $parentId,
                    'name'       => $subName,
                    'slug'       => Str::slug($subName) . '-' . fake()->numberBetween(10, 999),
                    'is_active'  => fake()->boolean(90),
                    'created_at' => now()->toDateTimeString(),
                    'updated_at' => now()->toDateTimeString(),
                ];
            }
        }

        DB::table('categories')->insert($children);
        $this->command->info('✅ ' . count($parents) . ' فئة رئيسية و ' . count($children) . ' فئة فرعية تم إنشاؤها.');
    }
}