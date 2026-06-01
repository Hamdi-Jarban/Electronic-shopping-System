<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_log', function (Blueprint $table) {
            $table->integer('log_id', true, true);
            $table->integer('inventory_id', false, true);
            $table->integer('change_quantity'); // موجب للزيادة، سالب للنقصان
            $table->string('change_reason', 50); // sale, restock, return, adjustment
            $table->dateTime('created_at')->useCurrent();

            $table->index('inventory_id', 'idx_inventory');
            $table->index('created_at', 'idx_created');

            $table->foreign('inventory_id', 'fk_log_inventory')
                ->references('inventory_id')->on('inventory')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        // Add CHECK constraint to ensure change_quantity is not zero
        DB::statement("ALTER TABLE `inventory_log` ADD CONSTRAINT `chk_quantity_not_zero` CHECK (`change_quantity` <> 0)");
        DB::statement("ALTER TABLE `inventory_log` COMMENT 'سجل حركات المخزون'");
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_log');
    }
};
