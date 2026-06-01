<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_status_log', function (Blueprint $table) {
            $table->integer('log_id', true, true);
            $table->integer('delivery_id', false, true);
            $table->integer('driver_id', false, true)->nullable();
            $table->string('delivery_status', 30);
            $table->dateTime('changed_at')->useCurrent();
            $table->text('notes')->nullable();

            $table->index('delivery_id', 'idx_delivery');
            $table->index('driver_id', 'idx_driver');

            $table->foreign('delivery_id', 'fk_dsl_delivery')
                ->references('delivery_id')->on('order_delivery')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('driver_id', 'fk_dsl_driver')
                ->references('driver_id')->on('delivery_driver')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `delivery_status_log` COMMENT 'سجل تغيير حالات التوصيل'");
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_status_log');
    }
};
