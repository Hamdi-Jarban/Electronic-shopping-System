<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventory_manager', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->primary(); // مرتبط بجدول الـ users الافتراضي
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->decimal('salary', 10, 2)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('warehouse_id')->references('warehouse_id')->on('warehouses')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_manager');
    }
};
