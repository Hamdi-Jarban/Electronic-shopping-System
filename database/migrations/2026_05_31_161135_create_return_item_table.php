<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('return_item', function (Blueprint $table) {
            $table->integer('return_item_id', true, true);
            $table->integer('return_id', false, true);
            $table->integer('order_item_id', false, true);
            $table->integer('quantity');

            $table->index('return_id', 'idx_return');

            $table->foreign('return_id', 'fk_ri_return')
                ->references('return_id')->on('return_request')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('order_item_id', 'fk_ri_order_item')
                ->references('order_item_id')->on('order_item')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `return_item` COMMENT 'بنود الإرجاع'");
    }

    public function down(): void
    {
        Schema::dropIfExists('return_item');
    }
};
