<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category', function (Blueprint $table) {
            $table->integer('category_id', true, true);
            $table->string('name', 255);
            $table->integer('parent_category_id', false, true)->nullable();

            $table->index('parent_category_id', 'idx_parent');

            // Self-referencing foreign key
            $table->foreign('parent_category_id', 'fk_category_parent')
                ->references('category_id')->on('category')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `category` COMMENT 'أقسام المنتجات'");
    }

    public function down(): void
    {
        Schema::dropIfExists('category');
    }
};
