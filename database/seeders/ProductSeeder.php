<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
  public function run(): void
  {
    fake()->locale('ar_SA');

    $this->command->info('📦 إنشاء 1000 منتج...');

    $brandIds = DB::table('brands')->pluck('id')->toArray();
    $categoryIds = DB::table('categories')->whereNotNull('parent_id')->pluck('id')->toArray();
    $userIds = DB::table('users')->pluck('id')->toArray();

    $productTypes = ['قميص',
      'بنطلون',
      'فستان',
      'حذاء',
      'حقيبة',
      'ساعة',
      'نظارة',
      'عطر',
      'كريم',
      'شامبو',
      'جاكيت',
      'تيشيرت',
      'بلوزة',
      'تنورة',
      'معطف',
      'بدلة',
      'حزام',
      'محفظة',
      'قبعة',
      'وشاح'];
    $styles = ['رجالي',
      'نسائي',
      'أطفال',
      'رياضي',
      'كلاسيكي',
      'عصري',
      'فاخر',
      'يومي',
      'موسمي',
      'رسمي'];
    $materials = ['قطن',
      'جلد',
      'صوف',
      'حرير',
      'كشمير',
      'بوليستر',
      'دنيم',
      'كتان',
      'مخمل',
      'نايلون'];
    $colors = ['أحمر',
      'أزرق',
      'أخضر',
      'أسود',
      'أبيض',
      'رمادي',
      'ذهبي',
      'فضي',
      'بنفسجي',
      'برتقالي',
      'وردي',
      'كحلي',
      'بيج',
      'بني',
      'زيتي'];
    $sizes = ['صغير',
      'متوسط',
      'كبير',
      'XL',
      'XXL',
      'مقاس موحد'];

    $descriptions = [
      'منتج عالي الجودة مصنوع من أفضل الخامات لضمان راحة العميل ورضاه التام.',
      'منتج عملي ومتين يتحمل الاستخدام اليومي المكثف مع الحفاظ على شكله الأصلي.',
      'صنع بعناية فائقة وبأيدي خبراء لضمان أعلى معايير الجودة والدقة.',
      'خيار مثالي لمن يبحث عن التميز والأناقة مع الأداء الممتاز.',
      'منتج فاخر يجمع بين الأصالة والحداثة، تم تصنيعه وفق أعلى المواصفات.',
    ];

    $reviews = [
      'منتج ممتاز وجودة عالية، أنصح به بشدة.',
      'جميل جداً ومطابق للوصف، شكراً لكم.',
      'جيد بشكل عام ولكن المقاس أصغر من المتوقع.',
      'منتج رائع وسعره مناسب جداً.',
      'خدمة سريعة وتغليف ممتاز، شكراً.',
      'أعجبني كثيراً، سأطلب مرة أخرى.',
      'لا بأس به، يوجد منتجات أفضل.',
      'رائع! أفضل من المتوقع بكثير.',
      'الخامة ممتازة والتوصيل سريع.',
      'أنصح الجميع بتجربته، يستحق كل ريال.',
    ];

    // إنشاء المنتجات
    $productsBatch = [];
    for ($i = 0; $i < 1000; $i++) {
      $type = fake()->randomElement($productTypes);
      $style = fake()->randomElement($styles);
      $name = $type . ' ' . $style . ' - ' . fake()->randomElement($materials);

      $productsBatch[] = [
        'brand_id' => fake()->randomElement($brandIds),
        'name' => $name,
        'slug' => Str::slug($name) . '-' . fake()->numberBetween(100, 999),
        'description' => fake()->randomElement($descriptions),
        'summary' => $type . ' ' . $style . ' مصنوع من ' . fake()->randomElement($materials) . '، متوفر بمقاسات وألوان متعددة.',
        'is_active' => fake()->boolean(90),
        'created_at' => fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s'),
        'updated_at' => now()->toDateTimeString(),
      ];
    }

    foreach (array_chunk($productsBatch, 200) as $chunk) {
      DB::table('products')->insert($chunk);
    }

    $products = DB::table('products')->pluck('id')->toArray();
    $this->command->info('✅ 1000 منتج تم إنشاؤهم.');

    // المتغيرات
    $this->command->info('🔀 إنشاء متغيرات المنتجات...');
    $variantBatch = [];
    $skuCounter = 50000;

    foreach ($products as $productId) {
      $variantCount = rand(2, 5);
      for ($j = 0; $j < $variantCount; $j++) {
        $attributes = [
          'اللون' => fake()->randomElement($colors),
          'المقاس' => fake()->randomElement($sizes),
          'الوزن' => fake()->numberBetween(200, 2000) . ' غرام',
        ];

        $variantBatch[] = [
          'product_id' => $productId,
          'sku' => 'SKU-' . $skuCounter++,
          'price' => fake()->randomFloat(2, 19, 999),
          'compare_at_price' => fake()->boolean(60) ? fake()->randomFloat(2, 29, 1299) : null,
          'attributes' => json_encode($attributes, JSON_UNESCAPED_UNICODE),
          'created_at' => now()->toDateTimeString(),
          'updated_at' => now()->toDateTimeString(),
        ];
      }
    }

    foreach (array_chunk($variantBatch, 500) as $chunk) {
      DB::table('product_variants')->insert($chunk);
    }

    // صور المنتجات
    $this->command->info('🖼️ إنشاء صور المنتجات...');
    $imageBatch = [];
    foreach ($products as $productId) {
      $imgCount = rand(1, 4);
      for ($k = 0; $k < $imgCount; $k++) {
        $imageBatch[] = [
          'product_id' => $productId,
          'variant_id' => null,
          'image_path' => 'products/' . fake()->uuid() . '.jpg',
          'is_featured' => $k === 0,
          'sort_order' => $k,
        ];
      }
    }

    foreach (array_chunk($imageBatch, 500) as $chunk) {
      DB::table('product_images')->insert($chunk);
    }

    // ربط الفئات
    $this->command->info('📂 ربط المنتجات بالفئات...');
    $categoryProduct = [];
    foreach ($products as $productId) {
      $cats = (array) array_rand(array_flip($categoryIds), rand(1, 3));
      foreach ($cats as $catId) {
        $categoryProduct[] = ['product_id' => $productId,
          'category_id' => $catId];
      }
    }
    foreach (array_chunk($categoryProduct, 500) as $chunk) {
      DB::table('category_product')->insert($chunk);
    }

    // المراجعات
    $this->command->info('⭐ إنشاء مراجعات المنتجات...');
    $reviewBatch = [];
    foreach ($products as $productId) {
      $reviewCount = rand(0, 5);
      for ($r = 0; $r < $reviewCount; $r++) {
        $reviewBatch[] = [
          'product_id' => $productId,
          'user_id' => fake()->randomElement($userIds),
          'rating' => fake()->numberBetween(1, 5),
          'comment' => fake()->randomElement($reviews),
          'created_at' => fake()->dateTimeBetween('-6 months', 'now')->format('Y-m-d H:i:s'),
        ];
      }
    }
    foreach (array_chunk($reviewBatch, 500) as $chunk) {
      DB::table('product_reviews')->insert($chunk);
    }

    $this->command->info('✅ اكتمل إنشاء المنتجات ومتغيراتها وصورها ومراجعاتها!');
  }
}