<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('nexus_voice_call_logs')) {
            return;
        }

        Schema::create('nexus_voice_call_logs', function (Blueprint $table) {
            $table->id();
            $table->string('provider')->index();
            $table->string('direction')->nullable()->index();
            $table->string('call_id')->nullable()->index();
            $table->unsignedBigInteger('lead_id')->nullable()->index();
            $table->unsignedBigInteger('contact_id')->nullable()->index();
            $table->string('phone_number')->nullable()->index();
            $table->string('status')->nullable()->index();
            $table->integer('duration_seconds')->nullable();
            $table->longText('summary')->nullable();
            $table->longText('transcript')->nullable();
            $table->string('recording_url')->nullable();
            $table->json('request_payload')->nullable();
            $table->json('response_payload')->nullable();
            $table->json('webhook_payload')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nexus_voice_call_logs');
    }
};
