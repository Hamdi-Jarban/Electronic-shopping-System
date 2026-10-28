<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_inventory_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_item_id')->constrained('order_items')->cascadeOnDelete();
            $table->foreignId('warehouse_inventory_id')->constrained('warehouse_inventory')->cascadeOnDelete();
            $table->unsignedInteger('quantity');
            $table->enum('status', ['reserved', 'released', 'fulfilled'])->default('reserved');
            $table->timestamp('created_at')->useCurrent();
            $table->unique(['order_item_id', 'warehouse_inventory_id'], 'order_item_inventory_reservation_unique');
            $table->index('warehouse_inventory_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_inventory_reservations');
    }
};
