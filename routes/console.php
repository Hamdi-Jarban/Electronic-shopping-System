<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Cart;

// مهمة مجدولة تعمل يومياً تلقائياً لتنظيف السلال المهجورة
Schedule::call(function () {

// احذف السلال (للشخص المجهول أو المسجل) التي لم يتم تحديثها منذ 7 أيام
$deletedCount = Cart::where('updated_at', '<', now()->subDays(7))->delete();

// (اختياري) كتابة تقرير في ملف الـ Log للتأكد من عمل النظام
Log::info("تم تنظيف قاعـدة البيانات بنجاح وحذف {$deletedCount} سلة مهجورة.");
})->daily();
Artisan::command('inspire', function () {
$this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');