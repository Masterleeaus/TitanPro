<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

if (!Schema::hasTable('work_jobs_assignments')) {
    Schema::create('work_jobs_assignments', function (Blueprint $table) {
        $table->unsignedBigInteger('company_id');
        $table->unsignedBigInteger('user_id');
        $table->unsignedBigInteger('team_id')->nullable();
        $table->unsignedBigInteger('created_by_team_id')->nullable();
        $table->bigIncrements('id');

        $table->unsignedBigInteger('job_id');
        $table->unsignedBigInteger('assignee_user_id')->nullable();
        $table->unsignedBigInteger('assignee_team_id')->nullable();
        $table->string('role', 40)->default('worker');
        $table->string('status', 40)->default('assigned');
        $table->json('meta_json')->nullable();

        $table->timestamps();

        $table->index(['company_id','user_id','job_id'], 'idx_work_job_assignments_job');

    });
}

    }

    public function down(): void
    {
        Schema::dropIfExists('work_jobs_assignments');

    }
};
