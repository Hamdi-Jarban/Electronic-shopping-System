<?php

use App\Models\CartItem;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    $deletedCount = CartItem::where('updated_at', '<', now()->subDays(30))->delete();
    Log::info("تم تنظيف {$deletedCount} عنصر سلة مهجور.");
})->daily();

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
