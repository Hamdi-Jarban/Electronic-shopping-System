<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupon_usage', function (Blueprint $table) {
            $table->integer('usage_id', true, true);
            $table->integer('coupon_id', false, true);
            $table->integer('user_id', false, true);
            $table->integer('order_id', false, true);
            $table->dateTime('used_at')->useCurrent();

            $table->index('coupon_id', 'idx_coupon');
            $table->index('user_id', 'idx_user');
            $table->index('order_id', 'idx_order');

            $table->foreign('coupon_id', 'fk_cu_coupon')
                ->references('coupon_id')->on('coupon')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('user_id', 'fk_cu_user')
                ->references('user_id')->on('user')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('order_id', 'fk_cu_order')
                ->references('order_id')->on('order_header')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `coupon_usage` COMMENT 'سجل استخدام الكوبونات'");
    }

    public function down(): void
    {
        Schema::dropIfExists('coupon_usage');
    }
};
