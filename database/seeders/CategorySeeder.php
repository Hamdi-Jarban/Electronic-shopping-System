<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // فئات رئيسية
        $parents = [];
        for ($i = 0; $i < 20; $i++) {
            $name = fake()->unique()->words(2, true);
            $parents[] = [
                'parent_id' => null,
                'name' => $name,
                'slug' => Str::slug($name),
                'is_active' => true,
                'created_at' => $this->randomDate(),
                'updated_at' => now(),
            ];
        }
        DB::table('categories')->insert($parents);
        $parentIds = DB::table('categories')->pluck('id');

        // 3-5 فئات فرعية لكل رئيسية
        $children = [];
        foreach ($parentIds as $parentId) {
            $count = rand(3, 5);
            for ($j = 0; $j < $count; $j++) {
                $name = fake()->unique()->words(2, true);
                $children[] = [
                    'parent_id' => $parentId,
                    'name' => $name,
                    'slug' => Str::slug($name),
                    'is_active' => fake()->boolean(90),
                    'created_at' => $this->randomDate(),
                    'updated_at' => now(),
                ];
            }
        }
        DB::table('categories')->insert($children);
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