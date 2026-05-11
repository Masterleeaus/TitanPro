<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('work_jobs_inspections')) {
            return;
        }

        Schema::create('work_jobs_inspections', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('company_id')->index();
            $table->bigInteger('user_id')->index();
            $table->bigInteger('team_id')->nullable()->index();
            $table->bigInteger('created_by_team_id')->nullable()->index();

            $table->unsignedBigInteger('job_id')->index();

            $table->string('inspection_type', 60)->default('post'); // pre, post, spot, rework
            $table->string('status', 40)->default('draft'); // draft, in_progress, submitted, approved, failed
            $table->string('title', 200)->nullable();
            $table->text('notes')->nullable();

            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->index(['company_id', 'user_id', 'job_id'], 'idx_jobs_inspections_tenant_job');
            $table->index(['company_id', 'user_id', 'status'], 'idx_jobs_inspections_status');
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('work_jobs_inspections')) {
            Schema::drop('work_jobs_inspections');
        }
    }
};
