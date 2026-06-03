<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user', function (Blueprint $table) {
            $table->integer('user_id', true, true); 
            $table->string('full_name', 255);
            $table->string('email', 255);
            $table->string('phone', 20)->nullable();

            $table->string('password', 255);

            $table->enum('role', ['customer', 'admin', 'support', 'inventory'])->default('customer');

            $table->rememberToken(); 
            $table->timestamp('email_verified_at')->nullable(); 

            $table->timestamps();

            $table->unique('email', 'uk_email');
            $table->index('role', 'idx_role');
        });

        DB::statement("ALTER TABLE `user` COMMENT 'جدول المستخدمين الأساسي الموحد للمتجر'");
    }

    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
