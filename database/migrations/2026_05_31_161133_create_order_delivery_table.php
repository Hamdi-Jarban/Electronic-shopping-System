<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_delivery', function (Blueprint $table) {
            $table->integer('delivery_id', true, true);
            $table->integer('order_id', false, true);
            $table->integer('driver_id', false, true)->nullable();
            $table->string('delivery_status', 30)->default('assigned'); // assigned, picked, in_transit, delivered
            $table->decimal('current_lat', 10, 8)->nullable();
            $table->decimal('current_long', 11, 8)->nullable();
            $table->dateTime('last_updated')->useCurrent()->useCurrentOnUpdate();

            $table->index('order_id', 'idx_order');
            $table->index('driver_id', 'idx_driver');

            $table->foreign('order_id', 'fk_od_order')
                ->references('order_id')->on('order_header')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('driver_id', 'fk_od_driver')
                ->references('driver_id')->on('delivery_driver')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `order_delivery` COMMENT 'عمليات التوصيل عبر مناديب الأسطول'");
    }

    public function down(): void
    {
        Schema::dropIfExists('order_delivery');
    }
};
