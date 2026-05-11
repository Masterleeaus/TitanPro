<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tz_automation_runs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('team_id');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();

            $table->string('agent_type', 80); // titan_work_automation
            $table->unsignedBigInteger('signal_id')->nullable();
            $table->string('idempotency_key', 190)->nullable();

            $table->string('status', 20)->default('ok'); // ok|failed|skipped
            $table->json('result_json')->nullable();
            $table->text('error')->nullable();

            $table->timestamps();

            $table->index(['team_id', 'agent_type']);
            $table->index(['team_id', 'signal_id']);
            $table->unique(['team_id', 'agent_type', 'signal_id']);
            $table->unique(['team_id', 'agent_type', 'idempotency_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tz_automation_runs');
    }
};
