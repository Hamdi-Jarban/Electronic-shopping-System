<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_status_history', function (Blueprint $table) {
            $table->integer('history_id', true, true);
            $table->integer('order_id', false, true);
            $table->string('status', 30);
            $table->dateTime('changed_at')->useCurrent();
            $table->string('changed_by', 255)->nullable();

            $table->index('order_id', 'idx_order');

            $table->foreign('order_id', 'fk_osh_order')
                ->references('order_id')->on('order_header')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `order_status_history` COMMENT 'سجل تغييرات حالة الطلب'");
    }

    public function down(): void
    {
        Schema::dropIfExists('order_status_history');
    }
};
