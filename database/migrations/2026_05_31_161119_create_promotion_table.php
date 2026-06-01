<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotion', function (Blueprint $table) {
            $table->integer('promotion_id', true, true);
            $table->string('promo_name', 255);
            $table->text('promo_description')->nullable();
            $table->enum('discount_type', ['percentage', 'fixed']);
            $table->decimal('discount_value', 10, 2);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->json('conditions_json')->nullable();
        });

        DB::statement("ALTER TABLE `promotion` COMMENT 'العروض الترويجية'");
    }

    public function down(): void
    {
        Schema::dropIfExists('promotion');
    }
};
