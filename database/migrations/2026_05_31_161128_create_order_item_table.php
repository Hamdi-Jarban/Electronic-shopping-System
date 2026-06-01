<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_item', function (Blueprint $table) {
            $table->integer('order_item_id', true, true);
            $table->integer('order_id', false, true);
            $table->integer('variant_id', false, true);
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);

            $table->index('order_id', 'idx_order');

            $table->foreign('order_id', 'fk_oi_order')
                ->references('order_id')->on('order_header')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('variant_id', 'fk_oi_variant')
                ->references('variant_id')->on('product_variant')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `order_item` COMMENT 'بنود الطلب'");
    }

    public function down(): void
    {
        Schema::dropIfExists('order_item');
    }
};
