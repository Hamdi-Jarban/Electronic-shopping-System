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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id('variant_id');
            $table->unsignedBigInteger('product_id')->index();
            $table->string('SKU', 100)->unique();
            $table->string('size_option', 50)->nullable();
            $table->string('color_option', 50)->nullable();
            $table->string('packaging', 50)->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('weight_kg', 8, 3)->nullable();
            $table->string('image_url', 500)->nullable(); // صورة مخصصة لهذا اللون/المقاس تحديدا
            $table->timestamps();

            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
