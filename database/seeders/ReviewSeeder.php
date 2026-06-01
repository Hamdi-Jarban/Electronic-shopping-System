<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;
use App\Models\Product;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();
        $products = Product::all();

        $comments = [
            'منتج ممتاز وجودة عالية',
            'جيد جداً، أنصح به',
            'متوسط، يوجد بدائل أفضل',
            'سعر مناسب وجودة مقبولة',
            'رائع! سأشتريه مرة أخرى',
            'لم يعجبني، الجودة أقل من المتوقع',
            'تغليف ممتاز وتوصيل سريع',
            'منتج أصلي 100%',
            'يستحق كل ريال',
            'أفضل من المنتجات المنافسة',
        ];

        $reviewCount = 0;

        // نأخذ كل المنتجات (20 فقط) بدل 50
        foreach ($products as $product) {
            // 1-8 تقييمات لكل منتج
            $reviewers = $customers->random(min(rand(1, 8), $customers->count()));

            foreach ($reviewers as $customer) {
                $exists = Review::where('user_id', $customer->user_id)
                    ->where('product_id', $product->product_id)
                    ->exists();

                if (!$exists) {
                    Review::create([
                        'user_id' => $customer->user_id,
                        'product_id' => $product->product_id,
                        'rating' => rand(1, 5),
                        'comment_text' => $comments[array_rand($comments)],
                        'created_at' => now()->subDays(rand(0, 90)),
                    ]);
                    $reviewCount++;
                }
            }
        }

        $this->command->info('✓ تم إنشاء ' . $reviewCount . ' تقييم');
    }
}
