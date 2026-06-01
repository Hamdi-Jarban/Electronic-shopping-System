<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variant', function (Blueprint $table) {
            $table->integer('variant_id', true, true);
            $table->integer('product_id', false, true);
            $table->string('SKU', 100);
            $table->string('size_option', 50)->nullable();
            $table->string('color_option', 50)->nullable();
            $table->string('packaging', 50)->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('weight_kg', 8, 3)->nullable();
            $table->string('image_url', 500)->nullable();

            $table->unique('SKU', 'uk_sku');
            $table->index('product_id', 'idx_product');

            $table->foreign('product_id', 'fk_variant_product')
                ->references('product_id')->on('product')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `product_variant` COMMENT 'متغيرات المنتج - حسب الحجم واللون والتغليف'");
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variant');
    }
};
