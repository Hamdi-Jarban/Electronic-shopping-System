<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('review_supplier', function (Blueprint $table) {
            $table->integer('review_id', true, true);
            $table->integer('user_id', false, true);
            $table->integer('supplier_id', false, true);
            $table->unsignedTinyInteger('rating'); // من 1 إلى 5
            $table->text('comment_text')->nullable();
            $table->dateTime('created_at')->useCurrent();

            $table->unique(['user_id', 'supplier_id'], 'uk_user_supplier');
            $table->index('supplier_id', 'idx_supplier');

            $table->foreign('user_id', 'fk_rs_user')
                ->references('user_id')->on('user')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('supplier_id', 'fk_rs_supplier')
                ->references('supplier_id')->on('supplier')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `review_supplier` COMMENT 'تقييمات الموردين من قبل الموظفين'");
    }

    public function down(): void
    {
        Schema::dropIfExists('review_supplier');
    }
};
