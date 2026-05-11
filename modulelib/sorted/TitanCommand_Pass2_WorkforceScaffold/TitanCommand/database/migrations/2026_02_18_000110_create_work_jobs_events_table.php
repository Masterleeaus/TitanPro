<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

if (!Schema::hasTable('work_jobs_events')) {
    Schema::create('work_jobs_events', function (Blueprint $table) {
        $table->unsignedBigInteger('company_id');
        $table->unsignedBigInteger('user_id');
        $table->unsignedBigInteger('team_id')->nullable();
        $table->unsignedBigInteger('created_by_team_id')->nullable();
        $table->bigIncrements('id');

        $table->unsignedBigInteger('job_id');
        $table->string('event_type', 60);
        $table->string('event_key', 80)->nullable();
        $table->text('message')->nullable();
        $table->json('payload_json')->nullable();
        $table->dateTime('occurred_at')->nullable();

        $table->timestamps();

        $table->index(['company_id','user_id','job_id'], 'idx_work_job_events_job');
        $table->index(['company_id','user_id','event_type'], 'idx_work_job_events_type');

    });
}

    }

    public function down(): void
    {
        Schema::dropIfExists('work_jobs_events');

    }
};
