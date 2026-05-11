<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

if (!Schema::hasTable('work_jobs_checklist_items')) {
    Schema::create('work_jobs_checklist_items', function (Blueprint $table) {
        $table->unsignedBigInteger('company_id');
        $table->unsignedBigInteger('user_id');
        $table->unsignedBigInteger('team_id')->nullable();
        $table->unsignedBigInteger('created_by_team_id')->nullable();
        $table->bigIncrements('id');

        $table->unsignedBigInteger('job_id');
        $table->unsignedBigInteger('checklist_id');
        $table->string('item_type', 40)->default('tick'); // tick|text|number|photo|signature
        $table->string('label', 200);
        $table->string('status', 40)->default('open'); // open|done|failed
        $table->text('value_text')->nullable();
        $table->decimal('value_number', 12, 2)->nullable();
        $table->json('meta_json')->nullable();

        $table->integer('sort_order')->default(0);
        $table->dateTime('checked_at')->nullable();

        $table->timestamps();

        $table->index(['company_id','user_id','job_id','checklist_id'], 'idx_work_job_checklist_items_job');

    });
}

    }

    public function down(): void
    {
        Schema::dropIfExists('work_jobs_checklist_items');

    }
};
