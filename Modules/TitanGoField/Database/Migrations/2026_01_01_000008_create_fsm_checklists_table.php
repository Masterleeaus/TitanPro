<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fsm_checklist_templates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->string('name');
            $table->string('vertical', 50)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('fsm_checklist_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('checklist_template_id');
            $table->string('label');
            $table->string('type', 30)->default('checkbox')->comment('checkbox|text|photo|signature');
            $table->boolean('required')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('checklist_template_id')->references('id')->on('fsm_checklist_templates')->cascadeOnDelete();
        });

        Schema::create('field_job_checklist_runs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedBigInteger('field_job_id');
            $table->unsignedBigInteger('checklist_template_id');
            $table->unsignedBigInteger('completed_by')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['company_id', 'field_job_id']);
            $table->foreign('field_job_id')->references('id')->on('field_jobs')->cascadeOnDelete();
        });

        Schema::create('field_job_checklist_responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('checklist_run_id');
            $table->unsignedBigInteger('checklist_item_id');
            $table->string('value')->nullable();
            $table->string('file_path')->nullable();
            $table->boolean('passed')->nullable();
            $table->timestamps();

            $table->foreign('checklist_run_id')->references('id')->on('field_job_checklist_runs')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('field_job_checklist_responses');
        Schema::dropIfExists('field_job_checklist_runs');
        Schema::dropIfExists('fsm_checklist_items');
        Schema::dropIfExists('fsm_checklist_templates');
    }
};
