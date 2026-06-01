<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_status_history', function (Blueprint $table) {
            $table->id('history_id');
            $table->unsignedInteger('order_id');
            $table->string('status', 30);
            $table->timestamp('changed_at')->useCurrent();
            $table->string('changed_by', 255)->default('System');

            $table->index('order_id', 'idx_order');

            $table->foreign('order_id', 'fk_osh_order')
                ->references('order_id')->on('order_header')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_status_history');
    }
};
