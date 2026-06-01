<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin', function (Blueprint $table) {
            $table->integer('user_id', false, true);
            $table->string('department', 100)->nullable();
            $table->string('permissions', 50)->default('limited');
            $table->decimal('salary', 10, 2)->nullable();

            $table->primary('user_id');

            $table->foreign('user_id', 'fk_admin_user')
                ->references('user_id')->on('user')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `admin` COMMENT 'بيانات المشرفين'");
    }

    public function down(): void
    {
        Schema::dropIfExists('admin');
    }
};
