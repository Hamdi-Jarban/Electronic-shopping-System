<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_loyalty', function (Blueprint $table) {
            $table->integer('customer_id', false, true);
            $table->integer('program_id', false, true);
            $table->integer('points_balance')->default(0);
            $table->string('tier', 20)->default('silver'); // silver, gold, platinum

            $table->primary(['customer_id', 'program_id']);

            $table->foreign('customer_id', 'fk_cl_customer')
                ->references('user_id')->on('customer')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('program_id', 'fk_cl_program')
                ->references('program_id')->on('loyalty_program')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `customer_loyalty` COMMENT 'اشتراكات العملاء في برامج الولاء'");
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_loyalty');
    }
};
