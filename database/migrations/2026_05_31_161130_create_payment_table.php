<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment', function (Blueprint $table) {
            $table->integer('payment_id', true, true);
            $table->integer('order_id', false, true);
            $table->integer('payment_method_id', false, true)->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('payment_status', 30)->default('pending'); // pending, success, failed, refunded
            $table->string('transaction_id', 255)->nullable();
            $table->dateTime('created_at')->useCurrent();

            $table->index('order_id', 'idx_order');

            $table->foreign('order_id', 'fk_payment_order')
                ->references('order_id')->on('order_header')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('payment_method_id', 'fk_payment_method')
                ->references('method_id')->on('payment_method')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `payment` COMMENT 'عمليات الدفع'");
    }

    public function down(): void
    {
        Schema::dropIfExists('payment');
    }
};
