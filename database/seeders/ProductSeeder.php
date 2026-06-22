<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $brandIds = DB::table('brands')->pluck('id');
        $categoryIds = DB::table('categories')->whereNotNull('parent_id')->pluck('id'); // فئات فرعية
        $userIds = DB::table('users')->pluck('id');

        $totalProducts = 1000;
        $productBatch = [];
        $createdProductIds = [];

        // إنشاء المنتجات
        for ($i = 0; $i < $totalProducts; $i++) {
            $name = fake()->unique()->words(3, true);
            $productBatch[] = [
                'brand_id' => $brandIds->random(),
                'name' => $name,
                'slug' => Str::slug($name) . '-' . $i, // ضمان التفرد
                'description' => fake()->optional(0.8)->realText(200),
                'summary' => fake()->optional()->sentence(10),
                'is_active' => fake()->boolean(85),
                'created_at' => $this->randomDate(),
                'updated_at' => now(),
            ];
        }

        foreach (array_chunk($productBatch, 200) as $chunk) {
            DB::table('products')->insert($chunk);
        }

        $products = DB::table('products')->pluck('id');

        // لكل منتج: متغيرات (2-5)
        $variantBatch = [];
        $skuCounter = 100000;
        foreach ($products as $productId) {
            $variantCount = rand(2, 5);
            for ($j = 0; $j < $variantCount; $j++) {
                $attributes = json_encode([
                    'color' => fake()->safeColorName(),
                    'size' => fake()->randomElement(['صغير', 'متوسط', 'كبير', 'XL']),
                ], JSON_UNESCAPED_UNICODE);
                $variantBatch[] = [
                    'product_id' => $productId,
                    'sku' => 'SKU-' . $skuCounter++,
                    'price' => fake()->randomFloat(2, 10, 500),
                    'compare_at_price' => fake()->optional(0.6)->randomFloat(2, 15, 600),
                    'attributes' => $attributes,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        foreach (array_chunk($variantBatch, 300) as $chunk) {
            DB::table('product_variants')->insert($chunk);
        }

        $variantIds = DB::table('product_variants')->pluck('id');

        // صور المنتجات (1-3 لكل منتج)
        $imageBatch = [];
        foreach ($products as $productId) {
            $imgCount = rand(1, 3);
            for ($k = 0; $k < $imgCount; $k++) {
                $imageBatch[] = [
                    'product_id' => $productId,
                    'variant_id' => $k === 0 ? null : collect($variantIds)->random(), // بعضها لمتغير
                    'image_path' => 'products/img_' . fake()->uuid() . '.jpg',
                    'is_featured' => $k === 0,
                    'sort_order' => $k,
                ];
            }
        }
        foreach (array_chunk($imageBatch, 300) as $chunk) {
            DB::table('product_images')->insert($chunk);
        }

        // ربط المنتجات بالفئات (1-3 فئات عشوائية)
        $categoryProduct = [];
        foreach ($products as $productId) {
            $cats = collect($categoryIds)->random(rand(1, 3));
            foreach ($cats as $catId) {
                $categoryProduct[] = ['product_id' => $productId, 'category_id' => $catId];
            }
        }
        DB::table('category_product')->insert($categoryProduct);

        // مراجعات عشوائية (لكل منتج 0-5 مراجعات)
        $reviewBatch = [];
        foreach ($products as $productId) {
            $reviewCount = rand(0, 5);
            for ($r = 0; $r < $reviewCount; $r++) {
                $reviewBatch[] = [
                    'product_id' => $productId,
                    'user_id' => $userIds->random(),
                    'rating' => rand(1, 5),
                    'comment' => fake()->optional()->realText(100),
                    'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
                ];
            }
        }
        foreach (array_chunk($reviewBatch, 300) as $chunk) {
            DB::table('product_reviews')->insert($chunk);
        }
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