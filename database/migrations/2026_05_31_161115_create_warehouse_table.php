<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouse', function (Blueprint $table) {
            $table->integer('warehouse_id', true, true);
            $table->string('name', 255);
            $table->text('location')->nullable();
        });

        DB::statement("ALTER TABLE `warehouse` COMMENT 'المستودعات'");
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouse');
    }
};
