<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_behavior', function (Blueprint $table) {
            $table->integer('behavior_id', true, true);
            $table->integer('user_id', false, true);
            $table->integer('product_id', false, true)->nullable();
            $table->string('action_type', 30); // view, cart, order, search
            $table->dateTime('event_time')->useCurrent();
            $table->integer('session_id', false, true)->nullable();

            $table->index('user_id', 'idx_user');
            $table->index('product_id', 'idx_product');
            $table->index('event_time', 'idx_event');

            $table->foreign('user_id', 'fk_ub_user')
                ->references('user_id')->on('user')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('product_id', 'fk_ub_product')
                ->references('product_id')->on('product')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `user_behavior` COMMENT 'سجل سلوك المستخدمين - لتغذية نظام التوصيات'");
    }

    public function down(): void
    {
        Schema::dropIfExists('user_behavior');
    }
};
