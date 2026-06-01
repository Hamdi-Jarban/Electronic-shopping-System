<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipment_tracking', function (Blueprint $table) {
            $table->integer('tracking_id', true, true);
            $table->integer('shipment_id', false, true);
            $table->string('tracking_status', 255)->nullable();
            $table->string('location', 255)->nullable();
            $table->dateTime('timestamp')->useCurrent();
            $table->text('notes')->nullable();

            $table->index('shipment_id', 'idx_shipment');

            $table->foreign('shipment_id', 'fk_st_shipment')
                ->references('shipment_id')->on('shipment')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `shipment_tracking` COMMENT 'تتبع الشحنات الخارجية'");
    }

    public function down(): void
    {
        Schema::dropIfExists('shipment_tracking');
    }
};
    