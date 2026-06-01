<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('return_request', function (Blueprint $table) {
            $table->integer('return_id', true, true);
            $table->integer('order_id', false, true);
            $table->string('return_status', 30)->default('requested'); // requested, approved, rejected, completed
            $table->text('reason_text')->nullable();
            $table->decimal('refund_amount', 10, 2)->nullable();
            $table->dateTime('request_date')->useCurrent();

            $table->index('order_id', 'idx_order');

            $table->foreign('order_id', 'fk_return_order')
                ->references('order_id')->on('order_header')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `return_request` COMMENT 'طلبات الإرجاع'");
    }

    public function down(): void
    {
        Schema::dropIfExists('return_request');
    }
};
