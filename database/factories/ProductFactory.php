<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        // التعديل: توليد اسم منتج من 3 كلمات عشوائية وجعل الحرف الأول كبيراً
        $name = ucfirst($this->faker->words(3, true)); 
        
        return [
            'brand_id' => \DB::table('brands')->inRandomOrder()->first()?->id ?? 1,
            'name' => $name,
            'slug' => Str::slug($name) . '-' . rand(100, 999),
            'description' => $this->faker->paragraph(3),
            'summary' => $this->faker->sentence(),
            'is_active' => true,
        ];
    }
}