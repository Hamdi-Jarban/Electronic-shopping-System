<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user', function (Blueprint $table) {
            // إنشاء المفتاح الأساسي كـ INT UNSIGNED متوافق مع قيود MySQL الصارمة
            $table->integer('user_id')->unsigned()->autoIncrement();

            $table->string('email', 255)->unique('uk_email');
            $table->string('password_hash', 255);
            $table->string('full_name', 255);
            $table->string('phone', 20)->nullable();
            $table->enum('role', ['customer', 'admin', 'support', 'inventory']);
            $table->timestamp('created_at')->useCurrent();

            // الفهارس (Indexes)
            $table->primary('user_id');
            $table->index('role', 'idx_role');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
