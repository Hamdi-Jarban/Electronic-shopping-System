<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        fake()->locale('ar_SA');

        $arabicBrands = [
            'الأصالة', 'الريادة', 'الفخامة', 'التميز', 'النجاح',
            'الأناقة', 'الذوق الرفيع', 'الجودة', 'الإبداع', 'التراث',
            'العصرية', 'الخليج', 'العربية', 'الشرقية', 'الغربية',
            'النخبة', 'الصدارة', 'المستقبل', 'النور', 'الزهراء',
            'اليمامة', 'السروات', 'الجزيرة', 'الواحة', 'النخيل',
            'الرياض', 'الحجاز', 'نجد', 'تهامة', 'العقيق',
            'اللؤلؤ', 'المرجان', 'الياقوت', 'الزمرد', 'الماس',
            'الفيروز', 'العنبر', 'المسك', 'العود', 'الورد',
            'الريحان', 'الياسمين', 'الفل', 'الخزامى', 'النرجس',
            'السيف', 'الدرع', 'الفارس', 'الصقر', 'العقاب',
        ];

        $brands = [];
        foreach ($arabicBrands as $name) {
            $brands[] = [
                'name'       => $name,
                'slug'       => Str::slug($name) . '-' . fake()->numberBetween(10, 99),
                'logo_url'   => fake()->boolean(70) ? 'brands/' . Str::slug($name) . '.png' : null,
                'is_active'  => true,
                'created_at' => fake()->dateTimeBetween('-2 years', 'now')->format('Y-m-d H:i:s'),
                'updated_at' => now()->toDateTimeString(),
            ];
        }

        DB::table('brands')->insert($brands);
        $this->command->info('✅ ' . count($brands) . ' علامة تجارية عربية تم إنشاؤها.');
    }
}