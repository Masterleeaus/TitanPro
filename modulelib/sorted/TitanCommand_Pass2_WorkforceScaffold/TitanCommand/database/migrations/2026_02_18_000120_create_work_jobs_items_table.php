<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

if (!Schema::hasTable('work_jobs_items')) {
    Schema::create('work_jobs_items', function (Blueprint $table) {
        $table->unsignedBigInteger('company_id');
        $table->unsignedBigInteger('user_id');
        $table->unsignedBigInteger('team_id')->nullable();
        $table->unsignedBigInteger('created_by_team_id')->nullable();
        $table->bigIncrements('id');

        $table->unsignedBigInteger('job_id');
        $table->string('item_type', 60);
        $table->string('title', 200);
        $table->text('description')->nullable();
        $table->string('status', 40)->default('open');
        $table->integer('sort_order')->default(0);

        $table->unsignedBigInteger('assigned_to_user_id')->nullable();
        $table->dateTime('due_at')->nullable();
        $table->dateTime('completed_at')->nullable();

        $table->json('meta_json')->nullable();

        $table->timestamps();

        $table->index(['company_id','user_id','job_id','item_type'], 'idx_work_job_items_job_type');

    });
}

    }

    public function down(): void
    {
        Schema::dropIfExists('work_jobs_items');

    }
};
