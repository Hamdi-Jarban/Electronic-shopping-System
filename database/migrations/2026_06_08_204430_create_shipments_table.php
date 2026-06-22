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
        Schema::create('shipments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
    $table->foreignId('warehouse_id')->nullable()->constrained('warehouses')->nullOnDelete();
    $table->string('carrier_name');
    $table->string('tracking_number')->nullable()->unique();
    $table->enum('status', ['pending', 'pickup', 'in_transit', 'out_for_delivery', 'delivered', 'failed'])->default('pending');
    $table->decimal('shipping_cost', 10, 2)->default(0.00);
    $table->decimal('shipping_price', 10, 2)->default(0.00);
    $table->timestamp('shipped_at')->nullable();
    $table->timestamp('delivered_at')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
