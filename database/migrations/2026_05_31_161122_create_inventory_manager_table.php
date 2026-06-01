<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_manager', function (Blueprint $table) {
            $table->integer('user_id', false, true);
            $table->integer('warehouse_id', false, true)->nullable();
            $table->decimal('salary', 10, 2)->nullable();

            $table->primary('user_id');

            $table->foreign('user_id', 'fk_inventory_manager_user')
                ->references('user_id')->on('user')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            // Foreign key to warehouse (added here instead of ALTER TABLE)
            $table->foreign('warehouse_id', 'fk_im_warehouse')
                ->references('warehouse_id')->on('warehouse')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `inventory_manager` COMMENT 'بيانات مسؤولي المخزون'");
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_manager');
    }
};
