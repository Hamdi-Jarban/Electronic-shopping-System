<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_delivery', function (Blueprint $table) {
            $table->id('delivery_id');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('driver_id')->nullable();
            $table->enum('delivery_status', ['assigned', 'picked', 'in_transit', 'delivered'])->default('assigned');
            $table->decimal('current_lat', 10, 8)->nullable();
            $table->decimal('current_long', 11, 8)->nullable();
            $table->timestamp('last_updated')->useCurrent()->useCurrentOnUpdate();

            $table->index('order_id', 'idx_order');
            $table->index('driver_id', 'idx_driver');

            $table->foreign('order_id', 'fk_od_order')
                ->references('order_id')->on('order_header')
                ->onDelete('restrict')->onUpdate('cascade');

            $table->foreign('driver_id', 'fk_od_driver')
                ->references('driver_id')->on('delivery_driver')
                ->onDelete('set null')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_delivery');
    }
};
