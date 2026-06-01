<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_header', function (Blueprint $table) {
            $table->integer('order_id', true, true);
            $table->integer('user_id', false, true);
            $table->dateTime('order_date')->useCurrent();
            $table->decimal('total_amount', 10, 2);
            $table->string('order_status', 30)->default('pending');
            $table->text('shipping_address')->nullable();
            $table->text('notes')->nullable();

            $table->index('user_id', 'idx_user');
            $table->index('order_status', 'idx_status');
            $table->index('order_date', 'idx_order_date');

            $table->foreign('user_id', 'fk_order_user')
                ->references('user_id')->on('user')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `order_header` COMMENT 'جدول الطلبات'");
    }

    public function down(): void
    {
        Schema::dropIfExists('order_header');
    }
};
