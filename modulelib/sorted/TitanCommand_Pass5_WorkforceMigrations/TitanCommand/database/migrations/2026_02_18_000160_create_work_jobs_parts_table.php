<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

if (!Schema::hasTable('work_jobs_parts')) {
    Schema::create('work_jobs_parts', function (Blueprint $table) {
        $table->unsignedBigInteger('company_id');
        $table->unsignedBigInteger('user_id');
        $table->unsignedBigInteger('team_id')->nullable();
        $table->unsignedBigInteger('created_by_team_id')->nullable();
        $table->bigIncrements('id');

        $table->unsignedBigInteger('job_id');
        $table->string('name', 200);
        $table->decimal('qty', 12, 2)->default(1);
        $table->decimal('unit_cost', 12, 2)->nullable();
        $table->string('status', 40)->default('requested'); // requested|ordered|used|returned
        $table->json('meta_json')->nullable();

        $table->timestamps();

        $table->index(['company_id','user_id','job_id'], 'idx_work_job_parts_job');

    });
}

    }

    public function down(): void
    {
        Schema::dropIfExists('work_jobs_parts');

    }
};
