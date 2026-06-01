<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loyalty_redemption', function (Blueprint $table) {
            $table->integer('redemption_id', true, true);
            $table->integer('customer_id', false, true);
            $table->integer('order_id', false, true)->nullable();
            $table->integer('points_used');
            $table->string('reward_description', 255)->nullable();
            $table->dateTime('redeemed_at')->useCurrent();

            $table->index('customer_id', 'idx_customer');

            $table->foreign('customer_id', 'fk_lr_customer')
                ->references('user_id')->on('customer')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('order_id', 'fk_lr_order')
                ->references('order_id')->on('order_header')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `loyalty_redemption` COMMENT 'عمليات استبدال نقاط الولاء'");
    }

    public function down(): void
    {
        Schema::dropIfExists('loyalty_redemption');
    }
};
