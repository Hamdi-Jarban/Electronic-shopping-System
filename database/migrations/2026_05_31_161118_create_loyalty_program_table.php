<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loyalty_program', function (Blueprint $table) {
            $table->integer('program_id', true, true);
            $table->string('program_name', 255);
            $table->decimal('points_per_currency', 10, 2)->default(1.00);
            $table->json('rules_json')->nullable();
        });

        DB::statement("ALTER TABLE `loyalty_program` COMMENT 'برامج الولاء'");
    }

    public function down(): void
    {
        Schema::dropIfExists('loyalty_program');
    }
};
