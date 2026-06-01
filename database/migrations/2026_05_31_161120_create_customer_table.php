<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer', function (Blueprint $table) {
            $table->integer('user_id', false, true);
            $table->date('date_of_birth')->nullable();
            $table->text('default_address')->nullable();
            $table->json('preferences_json')->nullable();

            $table->primary('user_id');

            $table->foreign('user_id', 'fk_customer_user')
                ->references('user_id')->on('user')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `customer` COMMENT 'بيانات العملاء'");
    }

    public function down(): void
    {
        Schema::dropIfExists('customer');
    }
};
