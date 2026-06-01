<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_tracking', function (Blueprint $table) {
            $table->integer('tracking_id', true, true);
            $table->integer('delivery_id', false, true);
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->dateTime('timestamp')->useCurrent();

            $table->index('delivery_id', 'idx_delivery');
            $table->index('timestamp', 'idx_timestamp');

            $table->foreign('delivery_id', 'fk_dt_delivery')
                ->references('delivery_id')->on('order_delivery')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `delivery_tracking` COMMENT 'نقاط التتبع الجغرافي الحي'");
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_tracking');
    }
};
