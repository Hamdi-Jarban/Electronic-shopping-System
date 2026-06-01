<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_driver', function (Blueprint $table) {
            $table->integer('driver_id', true, true);
            $table->string('driver_name', 255);
            $table->string('vehicle_number', 50)->nullable();
            $table->boolean('available')->default(true);
        });

        DB::statement("ALTER TABLE `delivery_driver` COMMENT 'مناديب التوصيل'");
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_driver');
    }
};
