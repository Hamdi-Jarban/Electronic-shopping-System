<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('warehouse_inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained('warehouses')->cascadeOnDelete();
            $table->foreignId('variant_id')->constrained('product_variants')->cascadeOnDelete();
            $table->integer('physical_qty')->default(0);
            $table->integer('reserved_qty')->default(0);
            $table->integer('low_stock_threshold')->default(5);
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->unique(['warehouse_id', 'variant_id'], 'unique_warehouse_variant');
            $table->index(['variant_id', 'warehouse_id'], 'idx_wh_inventory_lookup');
        });
    }

    public function down(): void {
        Schema::dropIfExists('warehouse_inventory');
    }
};