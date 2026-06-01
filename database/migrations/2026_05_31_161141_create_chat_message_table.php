<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_message', function (Blueprint $table) {
            $table->integer('message_id', true, true);
            $table->integer('session_id', false, true);
            $table->enum('sender_type', ['user', 'bot', 'agent']);
            $table->text('message_text');
            $table->dateTime('sent_at')->useCurrent();

            $table->index('session_id', 'idx_session');

            $table->foreign('session_id', 'fk_cm_session')
                ->references('session_id')->on('chat_session')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        DB::statement("ALTER TABLE `chat_message` COMMENT 'رسائل الدردشة'");
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_message');
    }
};
