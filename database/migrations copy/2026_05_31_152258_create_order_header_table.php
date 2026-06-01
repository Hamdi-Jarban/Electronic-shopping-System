<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_header', function (Blueprint $table) {
            $table->id('order_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('order_date')->useCurrent();
            $table->decimal('total_amount', 10, 2);
            $table->enum('order_status', ['pending', 'processing', 'packed', 'shipped', 'completed', 'cancelled'])->default('pending');
            $table->text('shipping_address')->nullable();
            $table->text('notes')->nullable();

            // الفهارس والقيود
            $table->index('user_id', 'idx_user');
            $table->index('order_status', 'idx_status');
            $table->index('order_date', 'idx_order_date');

            $table->foreign('user_id', 'fk_order_user')
                ->references('id')->on('users')
                ->onDelete('restrict')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_header');
    }
};
