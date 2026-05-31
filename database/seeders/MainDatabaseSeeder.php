<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\Supplier;

class MainDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ----------------------------------------------------
        // 1. إضافة البراندات (Brands)
        // ----------------------------------------------------
        $apple = Brand::firstOrCreate(['name' => 'Apple'], ['slug' => 'apple', 'logo_url' => 'apple.png']);
        $samsung = Brand::firstOrCreate(['name' => 'Samsung'], ['slug' => 'samsung', 'logo_url' => 'samsung.png']);
        $lg = Brand::firstOrCreate(['name' => 'LG'], ['slug' => 'lg', 'logo_url' => 'lg.png']);

        // ----------------------------------------------------
        // 2. إضافة الأقسام (Categories)
        // ----------------------------------------------------
        $electronics = Category::firstOrCreate(['name' => 'الإلكترونيات'], ['slug' => 'electronics']);

        $phones = Category::firstOrCreate(
            ['name' => 'الهواتف الذكية'],
            ['slug' => 'smart-phones', 'parent_category_id' => $electronics->category_id]
        );

        $screens = Category::firstOrCreate(
            ['name' => 'الشاشات والتلفزيونات'],
            ['slug' => 'smart-screens', 'parent_category_id' => $electronics->category_id]
        );

        // ----------------------------------------------------
        // 3. إضافة المستودعات (Warehouses)
        // ----------------------------------------------------
        $whSanaa = Warehouse::firstOrCreate(['name' => 'مستودع صنعاء الرئيسي'], ['location' => 'شارع الستين']);
        $whAden = Warehouse::firstOrCreate(['name' => 'مستودع عدن الفرعي'], ['location' => 'المعلا']);

        // ----------------------------------------------------
        // 4. إضافة الموردين (Suppliers)
        // ----------------------------------------------------
        $supHamdi = Supplier::firstOrCreate(
            ['email' => 'hamdi@supplier.com'],
            ['company_name' => 'شركة النخبة للتجارة', 'contact_person' => 'حمدي', 'phone' => '777777777']
        );

        $supGlobal = Supplier::firstOrCreate(
            ['email' => 'global@tech.com'],
            ['company_name' => 'العالمية للاستيراد', 'contact_person' => 'أحمد', 'phone' => '733333333']
        );

        // ----------------------------------------------------
        // 5. ضخ المنتجات ومتغيراتها ومخزونها
        // ----------------------------------------------------

        // --- المنتج الأول: iPhone 15 Pro ---
        $iphone = Product::create([
            'name' => 'iPhone 15 Pro',
            'slug' => 'iphone-15-pro-' . uniqid(),
            'description' => 'أحدث هواتف آبل بجسم من التيطانيوم ومعالج A17 Pro.',
            'base_image_url' => '/storage/products/iphone15.jpg',
            'is_active' => true,
            'brand_id' => $apple->brand_id
        ]);
        $iphone->categories()->attach([$electronics->category_id, $phones->category_id]);
        $iphone->suppliers()->attach($supHamdi->supplier_id, ['supply_price' => 1050.00, 'lead_time_days' => 5, 'minimum_order' => 2]);

        // متغيرات الآيفون ومخزونها
        $vIphone1 = $iphone->variants()->create(['SKU' => 'IPH15P-256-NAT', 'size_option' => '256GB', 'color_option' => 'Titanium Natural', 'price' => 1200.00]);
        $vIphone1->inventories()->create(['warehouse_id' => $whSanaa->warehouse_id, 'quantity_in_stock' => 35, 'reorder_level' => 5, 'reorder_quantity' => 10]);

        $vIphone2 = $iphone->variants()->create(['SKU' => 'IPH15P-512-BLK', 'size_option' => '512GB', 'color_option' => 'Space Black', 'price' => 1400.00]);
        $vIphone2->inventories()->create(['warehouse_id' => $whSanaa->warehouse_id, 'quantity_in_stock' => 15, 'reorder_level' => 3, 'reorder_quantity' => 5]);


        // --- المنتج الثاني: Samsung Galaxy S24 Ultra ---
        $s24 = Product::create([
            'name' => 'Galaxy S24 Ultra',
            'slug' => 'galaxy-s24-ultra-' . uniqid(),
            'description' => 'عملاق سامسونج مع كاميرا 200 ميجابكسل وميزات الذكاء الاصطناعي AI.',
            'base_image_url' => '/storage/products/hamdi.ico',
            'is_active' => true,
            'brand_id' => $samsung->brand_id
        ]);
        $s24->categories()->attach([$electronics->category_id, $phones->category_id]);
        $s24->suppliers()->attach($supHamdi->supplier_id, ['supply_price' => 950.00, 'lead_time_days' => 7, 'minimum_order' => 1]);

        // متغيرات السامسونج ومخزونها في مستودعين مختلفين
        $vS24_1 = $s24->variants()->create(['SKU' => 'S24U-512-GRY', 'size_option' => '512GB', 'color_option' => 'Titanium Gray', 'price' => 1150.00]);
        $vS24_1->inventories()->create(['warehouse_id' => $whSanaa->warehouse_id, 'quantity_in_stock' => 20, 'reorder_level' => 5, 'reorder_quantity' => 10]);
        $vS24_1->inventories()->create(['warehouse_id' => $whAden->warehouse_id, 'quantity_in_stock' => 10, 'reorder_level' => 2, 'reorder_quantity' => 5]);


        // --- المنتج الثالث: LG OLED C3 Smart TV ---
        $lgTv = Product::create([
            'name' => 'LG OLED C3 65 Inch',
            'slug' => 'lg-oled-c3-65-' . uniqid(),
            'description' => 'شاشة أوليد ذكية 65 بوصة مثالية للألعاب والسينما المنزلية بدقة 4K.',
            'base_image_url' => '/storage/products/hamdi.ico',
            'is_active' => true,
            'brand_id' => $lg->brand_id
        ]);
        $lgTv->categories()->attach([$electronics->category_id, $screens->category_id]);
        $lgTv->suppliers()->attach($supGlobal->supplier_id, ['supply_price' => 1400.00, 'lead_time_days' => 12, 'minimum_order' => 1]);

        // متغير الشاشة ومخزونها في مستودع عدن
        $vTv = $lgTv->variants()->create(['SKU' => 'LG-OLED-65C3', 'size_option' => '65 Inch', 'color_option' => 'Black', 'price' => 1650.00]);
        $vTv->inventories()->create(['warehouse_id' => $whAden->warehouse_id, 'quantity_in_stock' => 8, 'reorder_level' => 2, 'reorder_quantity' => 3]);
    }
}
