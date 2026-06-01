<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_category', function (Blueprint $table) {
            $table->integer('product_id', false, true);
            $table->integer('category_id', false, true);

            $table->primary(['product_id', 'category_id']);

            $table->foreign('product_id', 'fk_pc_product')
                ->references('product_id')->on('product')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('category_id', 'fk_pc_category')
                ->references('category_id')->on('category')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `product_category` COMMENT 'علاقة المنتجات بالأقسام - متعدد لمتعدد'");
    }

    public function down(): void
    {
        Schema::dropIfExists('product_category');
    }
};
