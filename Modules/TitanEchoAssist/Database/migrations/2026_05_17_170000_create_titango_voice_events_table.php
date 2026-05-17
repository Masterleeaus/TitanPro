<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('titango_voice_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('job_id')->nullable()->index();
            $table->string('action_key', 120)->nullable();
            $table->text('transcript');
            $table->string('matched_phrase', 255)->nullable();
            $table->enum('status', ['dispatched', 'confirmed', 'cancelled', 'failed'])->default('dispatched');
            $table->json('result')->nullable();
            $table->unsignedInteger('duration_ms')->default(0);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('titango_voice_events');
    }
};
