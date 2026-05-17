<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

if (!Schema::hasTable('work_jobs_links')) {
    Schema::create('work_jobs_links', function (Blueprint $table) {
        $table->unsignedBigInteger('company_id');
        $table->unsignedBigInteger('user_id');
        $table->unsignedBigInteger('team_id')->nullable();
        $table->unsignedBigInteger('created_by_team_id')->nullable();
        $table->bigIncrements('id');

        $table->unsignedBigInteger('job_id');
        $table->string('link_type', 60); // customer|site|invoice|quote|booking|asset|permit|staff
        $table->string('target_type', 80);
        $table->unsignedBigInteger('target_id');
        $table->json('meta_json')->nullable();

        $table->timestamps();

        $table->index(['company_id','user_id','job_id','link_type'], 'idx_work_job_links_job');

    });
}

    }

    public function down(): void
    {
        Schema::dropIfExists('work_jobs_links');

    }
};
