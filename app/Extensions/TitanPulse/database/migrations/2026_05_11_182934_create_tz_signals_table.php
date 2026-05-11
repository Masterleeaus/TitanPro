<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tz_signals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('team_id');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();

            $table->string('source', 120)->default('assistant.lifecycle');
            $table->string('type', 160);
            $table->string('signal_type', 160)->nullable();

            $table->string('subject_type', 120)->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();

            $table->json('payload_json')->nullable();
            $table->string('idempotency_key', 190)->nullable();
            $table->string('status', 20)->default('open');

            $table->timestamps();

            $table->index(['team_id', 'status']);
            $table->index(['team_id', 'type']);
            $table->index(['team_id', 'signal_type']);
            $table->index(['team_id', 'subject_type', 'subject_id']);
            $table->index(['team_id', 'idempotency_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tz_signals');
    }
};
