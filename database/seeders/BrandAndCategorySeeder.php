<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use App\Models\Category;

class BrandAndCategorySeeder extends Seeder
{
    public function run(): void
    {
        // ماركات معروفة
        $brands = [
            'سامسونج',
            'أبل',
            'سوني',
            'إل جي',
            'نايكي',
            'أديداس',
            'نستله',
            'بيبسي',
            'كوكاكولا',
            'يونيليفر',
            'هواوي',
            'شاومي',
            'ديل',
            'إتش بي',
            'لينوفو',
            'باناسونيك',
            'فيليبس',
            'توشيبا',
            'بلاك اند ديكر',
            'براون',
            'كولجيت',
            'بامبرز',
            'هنكل',
            'أريال',
            'تايد',
            'فيري',
            'كلوركس',
            'داوني',
        ];

        foreach ($brands as $name) {
            Brand::create(['name' => $name, 'logo_url' => null]);
        }

        // 10 ماركات إضافية بأسماء عربية
        for ($i = 1; $i <= 10; $i++) {
            Brand::create([
                'name' => 'العلامة التجارية ' . $i,
                'logo_url' => null,
            ]);
        }

        $this->command->info('✓ تم إنشاء ' . Brand::count() . ' علامة تجارية');

        // أقسام رئيسية مع أقسام فرعية
        $categories = [
            'إلكترونيات' => ['هواتف ذكية', 'حواسيب محمولة', 'أجهزة لوحية', 'سماعات', 'كاميرات', 'تلفزيونات', 'أجهزة ألعاب'],
            'ملابس' => ['رجالي', 'نسائي', 'أطفال', 'رياضي', 'أحذية', 'إكسسوارات'],
            'مواد غذائية' => ['معلبات', 'ألبان وأجبان', 'مشروبات', 'حلويات', 'مخبوزات', 'مجمدة', 'زيوت ودهون'],
            'منتجات تنظيف' => ['منظفات منزلية', 'عناية شخصية', 'منظفات ملابس', 'معطرات جو'],
            'أثاث' => ['غرف نوم', 'غرف معيشة', 'مطبخ', 'مكتبي', 'حدائق'],
            'ألعاب' => ['ألعاب أطفال', 'ألعاب إلكترونية', 'ألعاب تعليمية', 'دمى'],
            'كتب' => ['روايات', 'تعليمية', 'أطفال', 'تقنية'],
            'رياضة' => ['معدات رياضية', 'ملابس رياضية', 'تغذية رياضية'],
        ];

        foreach ($categories as $parentName => $children) {
            $parent = Category::create([
                'name' => $parentName,
                'parent_category_id' => null,
            ]);

            foreach ($children as $childName) {
                Category::create([
                    'name' => $childName,
                    'parent_category_id' => $parent->category_id,
                ]);
            }
        }

        $this->command->info('✓ تم إنشاء ' . Category::count() . ' قسم');
    }
}
