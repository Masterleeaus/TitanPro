<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ext_voice_channels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('provider')->default('twilio');
            $table->string('account_sid')->nullable();
            $table->string('auth_token')->nullable();
            $table->string('from_number')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('ext_voice_calls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('conversation_id')->nullable()->index();
            $table->string('call_sid')->unique();
            $table->string('from_number')->nullable();
            $table->string('to_number')->nullable();
            $table->string('direction')->nullable();
            $table->string('status')->nullable();
            $table->integer('duration')->nullable();
            $table->string('recording_url')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
        });

        Schema::create('ext_voice_transcripts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('voice_call_id')->index();
            $table->longText('transcript')->nullable();
            $table->json('provider_payload')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ext_voice_transcripts');
        Schema::dropIfExists('ext_voice_calls');
        Schema::dropIfExists('ext_voice_channels');
    }
};
