<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_staff', function (Blueprint $table) {
            $table->integer('user_id', false, true);
            $table->string('department', 100)->nullable();
            $table->boolean('is_online')->default(false);
            $table->decimal('salary', 10, 2)->nullable();

            $table->primary('user_id');

            $table->foreign('user_id', 'fk_support_staff_user')
                ->references('user_id')->on('user')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `support_staff` COMMENT 'بيانات موظفي الدعم'");
    }

    public function down(): void
    {
        Schema::dropIfExists('support_staff');
    }
};
