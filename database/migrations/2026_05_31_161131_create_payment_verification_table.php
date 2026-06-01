<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_verification', function (Blueprint $table) {
            $table->integer('verification_id', true, true);
            $table->integer('payment_id', false, true);
            $table->string('otp_hash', 255)->nullable();
            $table->dateTime('verified_at')->nullable();

            $table->foreign('payment_id', 'fk_pv_payment')
                ->references('payment_id')->on('payment')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `payment_verification` COMMENT 'التحقق الأمني لعمليات الدفع'");
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_verification');
    }
};
