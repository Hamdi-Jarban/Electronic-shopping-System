<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user', function (Blueprint $table) {
            $table->integer('user_id', true, true); // INT UNSIGNED AUTO_INCREMENT
            $table->string('email', 255);
            $table->string('password_hash', 255);
            $table->string('full_name', 255);
            $table->string('phone', 20)->nullable();
            $table->enum('role', ['customer', 'admin', 'support', 'inventory']);
            $table->dateTime('created_at')->useCurrent();

            // Indexes
            $table->unique('email', 'uk_email');
            $table->index('role', 'idx_role');
        });

        DB::statement("ALTER TABLE `user` COMMENT 'جدول المستخدمين الأساسي - كل أنواع المستخدمين'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
