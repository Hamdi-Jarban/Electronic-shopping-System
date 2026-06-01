<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_affinity', function (Blueprint $table) {
            $table->integer('user_id', false, true);
            $table->integer('product_id', false, true);
            $table->float('affinity_score')->default(0);
            $table->dateTime('last_calculated')->useCurrent()->useCurrentOnUpdate();

            $table->primary(['user_id', 'product_id']);

            $table->foreign('user_id', 'fk_pa_user')
                ->references('user_id')->on('user')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('product_id', 'fk_pa_product')
                ->references('product_id')->on('product')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `product_affinity` COMMENT 'نتائج تقارب المنتجات للتوصيات'");
    }

    public function down(): void
    {
        Schema::dropIfExists('product_affinity');
    }
};
