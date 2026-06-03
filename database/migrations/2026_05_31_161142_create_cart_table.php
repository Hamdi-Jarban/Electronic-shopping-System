<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart', function (Blueprint $table) {
            $table->integer('cart_id', true, true);

            $table->integer('user_id', false, true)->nullable();
            $table->string('session_id')->nullable();

            $table->dateTime('created_at')->useCurrent();

            $table->unique('user_id', 'uk_user_cart');
            $table->unique('session_id', 'uk_session_cart');

            $table->foreign('user_id', 'fk_cart_user')
                ->references('user_id')->on('user')    
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `cart` COMMENT 'سلة التسوق - تدعم المستخدمين المسجلين والزوار المؤقتين'");
    }

    public function down(): void
    {
        Schema::dropIfExists('cart');
    }
};
