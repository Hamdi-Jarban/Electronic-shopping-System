<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_supplier', function (Blueprint $table) {
            $table->integer('product_id', false, true);
            $table->integer('supplier_id', false, true);
            $table->decimal('supply_price', 10, 2);
            $table->integer('lead_time_days')->nullable();
            $table->integer('minimum_order')->default(1);

            $table->primary(['product_id', 'supplier_id']);

            $table->foreign('product_id', 'fk_ps_product')
                ->references('product_id')->on('product')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('supplier_id', 'fk_ps_supplier')
                ->references('supplier_id')->on('supplier')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `product_supplier` COMMENT 'علاقة المنتجات بالموردين - متعدد لمتعدد مع شروط التوريد'");
    }

    public function down(): void
    {
        Schema::dropIfExists('product_supplier');
    }
};
