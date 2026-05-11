<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

if (!Schema::hasTable('work_jobs_checklists')) {
    Schema::create('work_jobs_checklists', function (Blueprint $table) {
        $table->unsignedBigInteger('company_id');
        $table->unsignedBigInteger('user_id');
        $table->unsignedBigInteger('team_id')->nullable();
        $table->unsignedBigInteger('created_by_team_id')->nullable();
        $table->bigIncrements('id');

        $table->unsignedBigInteger('job_id');
        $table->string('title', 200);
        $table->string('status', 40)->default('open');
        $table->integer('sort_order')->default(0);
        $table->json('meta_json')->nullable();

        $table->timestamps();

        $table->index(['company_id','user_id','job_id'], 'idx_work_job_checklists_job');

    });
}

    }

    public function down(): void
    {
        Schema::dropIfExists('work_jobs_checklists');

    }
};
