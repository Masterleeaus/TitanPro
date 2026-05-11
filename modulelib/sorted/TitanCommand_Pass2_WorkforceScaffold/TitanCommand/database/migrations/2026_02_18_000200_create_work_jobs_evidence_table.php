<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('work_jobs_evidence')) {
            return;
        }

        Schema::create('work_jobs_evidence', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('company_id')->index();
            $table->bigInteger('user_id')->index();
            $table->bigInteger('team_id')->nullable()->index();
            $table->bigInteger('created_by_team_id')->nullable()->index();

            $table->unsignedBigInteger('job_id')->index();

            $table->string('evidence_type', 60); // photo, signature, note, file, signoff, inspection_photo, etc
            $table->string('label', 200)->nullable();
            $table->text('description')->nullable();

            // Storage pointer (can be URL, object key, external ref)
            $table->string('uri', 500)->nullable();
            $table->string('mime', 120)->nullable();

            $table->json('meta_json')->nullable();

            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->index(['company_id', 'user_id', 'job_id'], 'idx_jobs_evidence_tenant_job');
            $table->index(['company_id', 'user_id', 'evidence_type'], 'idx_jobs_evidence_type');
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('work_jobs_evidence')) {
            Schema::drop('work_jobs_evidence');
        }
    }
};
