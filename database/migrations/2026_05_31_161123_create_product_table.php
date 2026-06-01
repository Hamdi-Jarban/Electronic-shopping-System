<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product', function (Blueprint $table) {
            $table->integer('product_id', true, true);
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->integer('brand_id', false, true)->nullable();
            $table->string('base_image_url', 500)->nullable();
            $table->boolean('is_active')->default(true);

            $table->index('brand_id', 'idx_brand');
            $table->index('is_active', 'idx_active');

            $table->foreign('brand_id', 'fk_product_brand')
                ->references('brand_id')->on('brand')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `product` COMMENT 'جدول المنتجات الأساسي'");
    }

    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};
