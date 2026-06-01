<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_session', function (Blueprint $table) {
            $table->integer('session_id', true, true);
            $table->integer('user_id', false, true);
            $table->integer('support_staff_id', false, true)->nullable();
            $table->string('session_status', 20)->default('active'); // active, closed
            $table->dateTime('started_at')->useCurrent();
            $table->dateTime('ended_at')->nullable();

            $table->index('user_id', 'idx_user');
            $table->index('support_staff_id', 'idx_staff');

            $table->foreign('user_id', 'fk_cs_user')
                ->references('user_id')->on('user')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('support_staff_id', 'fk_cs_staff')
                ->references('user_id')->on('support_staff')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `chat_session` COMMENT 'جلسات الدردشة المباشرة'");
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_session');
    }
};
