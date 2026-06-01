<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('review', function (Blueprint $table) {
            $table->integer('review_id', true, true);
            $table->integer('user_id', false, true);
            $table->integer('product_id', false, true);
            $table->unsignedTinyInteger('rating'); // من 1 إلى 5
            $table->text('comment_text')->nullable();
            $table->dateTime('created_at')->useCurrent();

            $table->unique(['user_id', 'product_id'], 'uk_user_product');
            $table->index('product_id', 'idx_product');

            $table->foreign('user_id', 'fk_review_user')
                ->references('user_id')->on('user')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('product_id', 'fk_review_product')
                ->references('product_id')->on('product')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `review` COMMENT 'تقييمات العملاء للمنتجات'");
    }

    public function down(): void
    {
        Schema::dropIfExists('review');
    }
};
