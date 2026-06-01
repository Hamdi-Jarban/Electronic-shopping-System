<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brand', function (Blueprint $table) {
            $table->integer('brand_id', true, true);
            $table->string('name', 255);
            $table->string('logo_url', 500)->nullable();

            $table->unique('name', 'uk_brand_name');
        });

        DB::statement("ALTER TABLE `brand` COMMENT 'العلامات التجارية'");
    }

    public function down(): void
    {
        Schema::dropIfExists('brand');
    }
};
