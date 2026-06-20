<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('inventory_movements', function (Blueprint $table) {
    $table->id();
    $table->foreignId('warehouse_id')->constrained('warehouses')->cascadeOnDelete();
    $table->foreignId('variant_id')->constrained('product_variants')->cascadeOnDelete();
    $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
    $table->integer('quantity');
    $table->enum('type', ['inbound', 'outbound', 'adjustment', 'return', 'allocation']);
    $table->string('reference_type')->nullable();
    $table->unsignedBigInteger('reference_id')->nullable();
    $table->text('reason')->nullable();
    $table->timestamp('created_at')->useCurrent();

    $table->index(['reference_type', 'reference_id']);
    });
  }

  public function down(): void {
    Schema::dropIfExists('inventory_movements');
  }
};