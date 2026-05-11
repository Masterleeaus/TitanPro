<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('work_jobs_inspection_items')) {
            return;
        }

        Schema::create('work_jobs_inspection_items', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('company_id')->index();
            $table->bigInteger('user_id')->index();
            $table->bigInteger('team_id')->nullable()->index();
            $table->bigInteger('created_by_team_id')->nullable()->index();

            $table->unsignedBigInteger('job_id')->index();
            $table->unsignedBigInteger('inspection_id')->index();

            $table->string('item_key', 120)->nullable(); // optional stable key from a template
            $table->string('label', 200);
            $table->string('status', 40)->default('pending'); // pending, pass, fail, na
            $table->integer('score')->nullable(); // optional numeric scoring
            $table->text('notes')->nullable();

            $table->json('meta_json')->nullable();

            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->index(['company_id', 'user_id', 'inspection_id'], 'idx_jobs_inspection_items_tenant_inspection');
            $table->index(['company_id', 'user_id', 'status'], 'idx_jobs_inspection_items_status');
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('work_jobs_inspection_items')) {
            Schema::drop('work_jobs_inspection_items');
        }
    }
};
