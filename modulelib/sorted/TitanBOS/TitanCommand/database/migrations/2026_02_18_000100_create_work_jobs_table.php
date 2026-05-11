<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

if (!Schema::hasTable('work_jobs')) {
    Schema::create('work_jobs', function (Blueprint $table) {
        $table->unsignedBigInteger('company_id');
        $table->unsignedBigInteger('user_id');
        $table->unsignedBigInteger('team_id')->nullable();
        $table->unsignedBigInteger('created_by_team_id')->nullable();
        $table->bigIncrements('id');

        $table->string('job_ref', 80)->nullable();
        $table->string('title', 200);
        $table->text('description')->nullable();
        $table->string('status', 40)->default('open');
        $table->string('priority', 20)->default('normal');

        $table->unsignedBigInteger('customer_id')->nullable();
        $table->unsignedBigInteger('site_id')->nullable();

        $table->dateTime('scheduled_start')->nullable();
        $table->dateTime('scheduled_end')->nullable();

        $table->dateTime('completed_at')->nullable();
        $table->dateTime('archived_at')->nullable();

        $table->json('meta_json')->nullable();

        $table->timestamps();

        $table->index(['company_id','user_id'], 'idx_work_jobs_tenant');
        $table->index(['company_id','user_id','status'], 'idx_work_jobs_status');
        $table->unique(['company_id','user_id','job_ref'], 'uq_work_jobs_ref');

    });
}

    }

    public function down(): void
    {
        Schema::dropIfExists('work_jobs');

    }
};
