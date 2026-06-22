<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [];
        for ($i = 0; $i < 50; $i++) {
            $name = fake()->unique()->company();
            $brands[] = [
                'name' => $name,
                'slug' => Str::slug($name),
                'logo_url' => fake()->optional(0.7)->imageUrl(200, 200, 'brand', true),
                'is_active' => fake()->boolean(80),
                'created_at' => $this->randomDate(),
                'updated_at' => now(),
            ];
        }
        DB::table('brands')->insert($brands);
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