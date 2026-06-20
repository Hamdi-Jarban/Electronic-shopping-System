<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('variant_id')->constrained('product_variants'); 
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
        });
    }

    public function down(): void {
        Schema::dropIfExists('order_items');
    }
};