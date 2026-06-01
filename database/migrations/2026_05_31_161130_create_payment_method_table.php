<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_method', function (Blueprint $table) {
            $table->integer('method_id', true, true);
            $table->integer('user_id', false, true);
            $table->string('payment_type', 30); // card, wallet, cod
            $table->text('details_encrypted')->nullable();
            $table->boolean('is_default')->default(false);

            $table->index('user_id', 'idx_user');

            $table->foreign('user_id', 'fk_pm_user')
                ->references('user_id')->on('user')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `payment_method` COMMENT 'طرق الدفع المحفوظة للعملاء'");
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_method');
    }
};
