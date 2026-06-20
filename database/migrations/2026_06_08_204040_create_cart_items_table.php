<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('session_token')->nullable();
            $table->foreignId('variant_id')->constrained('product_variants')->cascadeOnDelete();
            $table->integer('quantity')->unsigned()->default(1);
            $table->timestamps();

            $table->unique(['user_id', 'variant_id'], 'unique_user_variant');
            $table->unique(['session_token', 'variant_id'], 'unique_session_variant');
            $table->index('session_token', 'idx_cart_session');
        });
    }

    public function down(): void {
        Schema::dropIfExists('cart_items');
    }
};