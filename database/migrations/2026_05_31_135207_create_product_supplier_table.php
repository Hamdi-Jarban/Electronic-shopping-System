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
        Schema::create('product_supplier', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('supplier_id');
            $table->decimal('supply_price', 10, 2);
            $table->integer('lead_time_days')->nullable();
            $table->integer('minimum_order')->default(1);

            $table->primary(['product_id', 'supplier_id']);
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('supplier_id')->references('supplier_id')->on('suppliers')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_supplier');
    }
};
