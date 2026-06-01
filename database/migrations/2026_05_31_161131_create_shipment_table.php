<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipment', function (Blueprint $table) {
            $table->integer('shipment_id', true, true);
            $table->integer('order_id', false, true);
            $table->integer('carrier_id', false, true)->nullable();
            $table->string('tracking_number', 255)->nullable();
            $table->string('shipment_status', 30)->default('packed'); // packed, in_transit, delivered
            $table->dateTime('estimated_delivery')->nullable();

            $table->index('order_id', 'idx_order');

            $table->foreign('order_id', 'fk_shipment_order')
                ->references('order_id')->on('order_header')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('carrier_id', 'fk_shipment_carrier')
                ->references('carrier_id')->on('carrier')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `shipment` COMMENT 'الشحنات عبر شركات خارجية'");
    }

    public function down(): void
    {
        Schema::dropIfExists('shipment');
    }
};
