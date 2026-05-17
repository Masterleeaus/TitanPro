<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('nexus_call_sessions')) {
            Schema::create('nexus_call_sessions', function (Blueprint $table) {
                $table->id();
                $table->string('provider')->nullable()->index();
                $table->string('provider_call_id')->nullable()->index();
                $table->string('direction')->nullable()->index();
                $table->unsignedBigInteger('lead_id')->nullable()->index();
                $table->unsignedBigInteger('contact_id')->nullable()->index();
                $table->string('from_number')->nullable()->index();
                $table->string('to_number')->nullable()->index();
                $table->string('status')->default('new')->index();
                $table->string('outcome')->nullable()->index();
                $table->integer('duration_seconds')->nullable();
                $table->longText('summary')->nullable();
                $table->longText('transcript')->nullable();
                $table->json('payload')->nullable();
                $table->timestamp('started_at')->nullable();
                $table->timestamp('ended_at')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('nexus_call_events')) {
            Schema::create('nexus_call_events', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('call_session_id')->nullable()->index();
                $table->string('provider')->nullable()->index();
                $table->string('provider_event_id')->nullable()->index();
                $table->string('event_type')->index();
                $table->string('status')->nullable()->index();
                $table->json('payload')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('nexus_callback_requests')) {
            Schema::create('nexus_callback_requests', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('lead_id')->nullable()->index();
                $table->unsignedBigInteger('contact_id')->nullable()->index();
                $table->unsignedBigInteger('call_session_id')->nullable()->index();
                $table->string('name')->nullable();
                $table->string('phone')->nullable()->index();
                $table->string('status')->default('open')->index();
                $table->string('priority')->default('normal')->index();
                $table->timestamp('due_at')->nullable()->index();
                $table->longText('notes')->nullable();
                $table->json('payload')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('nexus_call_recordings')) {
            Schema::create('nexus_call_recordings', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('call_session_id')->nullable()->index();
                $table->string('provider')->nullable()->index();
                $table->string('provider_recording_id')->nullable()->index();
                $table->string('recording_url')->nullable();
                $table->string('local_path')->nullable();
                $table->integer('duration_seconds')->nullable();
                $table->string('status')->default('available')->index();
                $table->timestamp('fetched_at')->nullable();
                $table->timestamp('expires_at')->nullable()->index();
                $table->json('payload')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('nexus_call_recordings');
        Schema::dropIfExists('nexus_callback_requests');
        Schema::dropIfExists('nexus_call_events');
        Schema::dropIfExists('nexus_call_sessions');
    }
};
