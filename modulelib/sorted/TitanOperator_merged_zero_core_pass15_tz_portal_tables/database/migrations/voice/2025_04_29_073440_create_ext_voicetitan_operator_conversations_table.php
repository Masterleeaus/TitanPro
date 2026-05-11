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
        Schema::create('tz_portal_voice_conversations', function (Blueprint $table) {
            $table->id();
            $table->uuid('operator_uuid');
            $table->foreign('operator_uuid')
                ->references('uuid')
                ->on('tz_portal_operator_voice_bots')
                ->cascadeOnDelete();
            $table->string('conversation_id');
            $table->string('status')->default('processing');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tz_portal_voice_conversations');
    }
};
