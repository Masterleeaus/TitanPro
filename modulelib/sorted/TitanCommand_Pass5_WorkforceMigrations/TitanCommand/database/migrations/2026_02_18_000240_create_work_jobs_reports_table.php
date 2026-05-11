<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('work_jobs_reports')) {
            return;
        }

        Schema::create('work_jobs_reports', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('company_id')->index();
            $table->bigInteger('user_id')->index();
            $table->bigInteger('team_id')->nullable()->index();
            $table->bigInteger('created_by_team_id')->nullable()->index();

            $table->string('report_type', 80); // performance, compliance, proof_pack, summary
            $table->string('label', 200)->nullable();

            $table->json('params_json')->nullable();
            $table->json('result_json')->nullable();

            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->index(['company_id','user_id','report_type'], 'idx_jobs_reports_type');
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('work_jobs_reports')) {
            Schema::drop('work_jobs_reports');
        }
    }
};
