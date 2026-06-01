<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_item', function (Blueprint $table) {
            $table->id('order_item_id');
            $table->unsignedBigInteger('order_id');

            // 💡 غيرنا النوع هنا ليكون متوافقاً مع الجداول السابقة لو كانت Integer عادي
            $table->unsignedInteger('variant_id');

            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);

            $table->index('order_id', 'idx_order');
            $table->index('variant_id', 'idx_variant');

            // علاقة رأس الطلب (نجحت سابقاً)
            $table->foreign('order_id', 'fk_oi_order')
                ->references('order_id')->on('order_header')
                ->onDelete('cascade')->onUpdate('cascade');

            // 💡 تعديل الربط: نفترض هنا أن المفتاح الأساسي لجدول المتغيرات اسمه variant_id
            $table->foreign('variant_id', 'fk_oi_variant')
                ->references('variant_id')->on('product_variants')
                ->onDelete('restrict')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_item');
    }
};
