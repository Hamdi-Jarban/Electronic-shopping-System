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
        Schema::create('products', function (Blueprint $table) {
            $table->id('product_id');
            $table->string('name');
            $table->string('slug')->unique(); // تم إضافته للـ SEO
            $table->text('description')->nullable();
            $table->string('base_image_url', 500)->nullable(); // الصورة المصغرة الأساسية
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedBigInteger('brand_id')->nullable()->index();
            $table->timestamps();

            $table->foreign('brand_id')->references('brand_id')->on('brands')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
