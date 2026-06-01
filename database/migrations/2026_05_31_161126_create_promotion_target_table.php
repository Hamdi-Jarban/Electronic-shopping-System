<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotion_target', function (Blueprint $table) {
            $table->integer('target_id', true, true);
            $table->integer('promotion_id', false, true);
            $table->enum('target_type', ['product', 'category', 'brand']);
            $table->integer('target_entity_id', false, true);

            $table->index('promotion_id', 'idx_promotion');

            $table->foreign('promotion_id', 'fk_pt_promotion')
                ->references('promotion_id')->on('promotion')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `promotion_target` COMMENT 'ربط العروض بالمنتجات أو الأقسام أو العلامات'");
    }

    public function down(): void
    {
        Schema::dropIfExists('promotion_target');
    }
};
