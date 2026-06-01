<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_method', function (Blueprint $table) {
            $table->id('method_id');
            $table->unsignedInteger('user_id');
            $table->enum('payment_type', ['card', 'wallet', 'cod']);
            $table->text('details_encrypted')->nullable();
            $table->tinyInteger('is_default')->default(0);

            $table->index('user_id', 'idx_user');

            $table->foreign('user_id', 'fk_pm_user')
                ->references('user_id')->on('user')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_method');
    }
};
