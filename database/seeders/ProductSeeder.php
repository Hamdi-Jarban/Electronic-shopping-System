<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Supplier;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $brands = Brand::all();
        $categories = Category::all();
        $suppliers = Supplier::all();

        $products = [
            'هاتف ذكي' => [1200, 1500, 2000],
            'حاسوب محمول' => [3000, 4500, 6000],
            'جهاز لوحي' => [800, 1200, 1800],
            'سماعة لاسلكية' => [100, 200, 350],
            'كاميرا رقمية' => [1500, 2500, 4000],
            'تلفزيون' => [2000, 3500, 5000],
            'قميص رجالي' => [50, 80, 120],
            'فستان نسائي' => [100, 200, 350],
            'حذاء رياضي' => [150, 300, 500],
            'حليب' => [5, 8, 12],
            'خبز' => [2, 3, 5],
            'أرز' => [15, 25, 40],
            'منظف منزلي' => [10, 15, 25],
            'شامبو' => [12, 20, 35],
            'سرير' => [800, 1200, 2000],
            'طاولة' => [300, 500, 800],
            'لعبة أطفال' => [25, 50, 100],
            'رواية' => [20, 35, 50],
            'كرة قدم' => [40, 70, 120],
            'مشروب غازي' => [3, 5, 7],
        ];

        $index = 1;
        foreach ($products as $name => $prices) {
            $product = Product::create([
                'name' => $name . ' موديل ' . date('Y'),
                'description' => 'وصف المنتج: ' . $name . ' عالي الجودة مع ضمان لمدة سنة كاملة. مناسب للاستخدام اليومي.',
                'brand_id' => $brands->random()->brand_id,
                'base_image_url' => null,
                'is_active' => true,
            ]);

            // ربط بأقسام عشوائية
            $product->categories()->attach(
                $categories->random(rand(1, 3))->pluck('category_id')->toArray()
            );

            // إنشاء متغيرات (أحجام أو ألوان مختلفة)
            $sizes = ['صغير', 'وسط', 'كبير'];
            $colors = ['أحمر', 'أزرق', 'أسود', 'أبيض'];

            for ($v = 0; $v < count($prices); $v++) {
                ProductVariant::create([
                    'product_id' => $product->product_id,
                    'SKU' => 'SKU-' . str_pad($index, 5, '0', STR_PAD_LEFT) . '-' . $v,
                    'size_option' => $sizes[$v] ?? null,
                    'color_option' => $colors[$v % 4],
                    'packaging' => rand(0, 1) ? 'صندوق' : 'كيس',
                    'price' => $prices[$v],
                    'weight_kg' => rand(1, 100) / 10,
                    'image_url' => null,
                ]);
            }

            // ربط بموردين
            $product->suppliers()->attach(
                $suppliers->random(rand(1, 3))->pluck('supplier_id')->toArray(),
                [
                    'supply_price' => $prices[0] * 0.6,
                    'lead_time_days' => rand(1, 14),
                    'minimum_order' => rand(1, 50),
                ]
            );

            $index++;
        }

        $this->command->info('✓ تم إنشاء ' . Product::count() . ' منتج');
        $this->command->info('✓ تم إنشاء ' . ProductVariant::count() . ' متغير منتج');
    }
}
