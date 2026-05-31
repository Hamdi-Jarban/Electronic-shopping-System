<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment', function (Blueprint $table) {
            $table->id('payment_id');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('payment_method_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->enum('payment_status', ['pending', 'success', 'failed', 'refunded'])->default('pending');
            $table->string('transaction_id', 255)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('order_id', 'idx_order');

            $table->foreign('order_id', 'fk_payment_order')
                ->references('order_id')->on('order_header')
                ->onDelete('restrict')->onUpdate('cascade');

            $table->foreign('payment_method_id', 'fk_payment_method')
                ->references('method_id')->on('payment_method')
                ->onDelete('set null')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment');
    }
};
