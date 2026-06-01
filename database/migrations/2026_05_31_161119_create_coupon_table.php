<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupon', function (Blueprint $table) {
            $table->integer('coupon_id', true, true);
            $table->string('code', 50);
            $table->enum('discount_type', ['percentage', 'fixed']);
            $table->decimal('value', 10, 2);
            $table->integer('max_uses')->default(100);
            $table->integer('current_uses')->default(0);
            $table->dateTime('valid_from');
            $table->dateTime('valid_to');

            $table->unique('code', 'uk_code');
        });

        DB::statement("ALTER TABLE `coupon` COMMENT 'الكوبونات'");
    }

    public function down(): void
    {
        Schema::dropIfExists('coupon');
    }
};
