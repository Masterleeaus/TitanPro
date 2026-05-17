<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('work_jobs_templates')) {
            Schema::create('work_jobs_templates', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('company_id');
                $table->bigInteger('user_id');
                $table->bigInteger('team_id')->nullable();
                $table->bigInteger('created_by_team_id')->nullable();

                $table->string('template_type', 60); // checklist|inspection|evidence_rule
                $table->string('title', 200);
                $table->string('status', 40)->default('active');
                $table->json('meta_json')->nullable();
                $table->timestamps();

                $table->index(['company_id','user_id']);
                $table->index(['company_id','user_id','template_type']);
            });
        }

        if (!Schema::hasTable('work_jobs_template_items')) {
            Schema::create('work_jobs_template_items', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('company_id');
                $table->bigInteger('user_id');
                $table->bigInteger('team_id')->nullable();
                $table->bigInteger('created_by_team_id')->nullable();

                $table->bigInteger('template_id');
                $table->string('item_type', 60); // checklist_item|inspection_item|evidence_requirement
                $table->string('label', 255);
                $table->string('status', 40)->default('active');
                $table->integer('sort_order')->default(0);
                $table->json('schema_json')->nullable(); // typed requirements
                $table->timestamps();

                $table->index(['company_id','user_id']);
                $table->index(['company_id','user_id','template_id']);
            });
        }
    }

    public function down(): void
    {
        // keep idempotent / safe: drop only if exists
        if (Schema::hasTable('work_jobs_template_items')) {
            Schema::drop('work_jobs_template_items');
        }
        if (Schema::hasTable('work_jobs_templates')) {
            Schema::drop('work_jobs_templates');
        }
    }
};
