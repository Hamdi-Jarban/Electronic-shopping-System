<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('coupon_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained('coupons')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); 
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete(); 
            $table->decimal('discount_obtained', 10, 2); 
            $table->timestamp('used_at')->useCurrent();
        });
    }

    public function down(): void {
        Schema::dropIfExists('coupon_usages');
    }
};