<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_audit', function (Blueprint $table) {
            $table->integer('audit_id', true, true);
            $table->integer('product_id', false, true);
            $table->integer('user_id', false, true);
            $table->string('action', 20); // INSERT, UPDATE, DELETE
            $table->json('old_data_json')->nullable();
            $table->json('new_data_json')->nullable();
            $table->dateTime('changed_at')->useCurrent();

            $table->index('product_id', 'idx_product');
            $table->index('user_id', 'idx_user');
            $table->index('changed_at', 'idx_changed_at');

            $table->foreign('product_id', 'fk_audit_product')
                ->references('product_id')->on('product')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('user_id', 'fk_audit_user')
                ->references('user_id')->on('user')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `product_audit` COMMENT 'سجل تعديلات المنتجات'");
    }

    public function down(): void
    {
        Schema::dropIfExists('product_audit');
    }
};
