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
        Schema::create('inventory_logs', function (Blueprint $table) {
            $table->id('log_id');
            $table->unsignedBigInteger('inventory_id')->index();
            $table->integer('change_quantity');
            $table->string('change_reason', 50); // sale, restock, return, adjustment
            $table->timestamp('created_at')->useCurrent()->index();

            $table->foreign('inventory_id')->references('inventory_id')->on('inventories')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_logs');
    }
};
