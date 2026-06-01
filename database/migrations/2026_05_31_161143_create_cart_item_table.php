<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_item', function (Blueprint $table) {
            $table->integer('cart_item_id', true, true);
            $table->integer('cart_id', false, true);
            $table->integer('variant_id', false, true);
            $table->integer('quantity')->default(1);

            $table->unique(['cart_id', 'variant_id'], 'uk_cart_variant');

            $table->foreign('cart_id', 'fk_ci_cart')
                ->references('cart_id')->on('cart')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('variant_id', 'fk_ci_variant')
                ->references('variant_id')->on('product_variant')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `cart_item` COMMENT 'عناصر سلة التسوق'");
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_item');
    }
};
