<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory', function (Blueprint $table) {
            $table->integer('inventory_id', true, true);
            $table->integer('variant_id', false, true);
            $table->integer('warehouse_id', false, true);
            $table->integer('quantity_in_stock')->default(0);
            $table->integer('reorder_level')->default(10);
            $table->integer('reorder_quantity')->default(50);
            $table->dateTime('last_updated')->useCurrent()->useCurrentOnUpdate();

            $table->unique(['variant_id', 'warehouse_id'], 'uk_variant_warehouse');
            $table->index('warehouse_id', 'idx_warehouse');

            $table->foreign('variant_id', 'fk_inventory_variant')
                ->references('variant_id')->on('product_variant')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('warehouse_id', 'fk_inventory_warehouse')
                ->references('warehouse_id')->on('warehouse')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `inventory` COMMENT 'المخزون - يربط المتغيرات بالمستودعات'");
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};
