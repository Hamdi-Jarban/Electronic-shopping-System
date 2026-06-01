<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_audit', function (Blueprint $table) {
            $table->id('audit_id');
            $table->unsignedBigInteger('product_id')->index();
            $table->unsignedBigInteger('user_id')->index(); // الموظف الذي قام بالتعديل
            $table->string('action', 20); // INSERT, UPDATE, DELETE
            $table->json('old_data_json')->nullable();
            $table->json('new_data_json')->nullable();
            $table->timestamp('changed_at')->useCurrent()->index();

            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_audit');
    }
};
