<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supplier', function (Blueprint $table) {
            $table->integer('supplier_id', true, true);
            $table->string('company_name', 255);
            $table->string('contact_person', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('phone', 20)->nullable();
            $table->decimal('rating_avg', 3, 2)->nullable();
        });

        DB::statement("ALTER TABLE `supplier` COMMENT 'الموردين'");
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier');
    }
};
