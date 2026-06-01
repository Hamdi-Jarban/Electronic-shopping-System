<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carrier', function (Blueprint $table) {
            $table->integer('carrier_id', true, true);
            $table->string('name', 255);
            $table->string('api_endpoint', 500)->nullable();
        });

        DB::statement("ALTER TABLE `carrier` COMMENT 'شركات الشحن الخارجي'");
    }

    public function down(): void
    {
        Schema::dropIfExists('carrier');
    }
};
