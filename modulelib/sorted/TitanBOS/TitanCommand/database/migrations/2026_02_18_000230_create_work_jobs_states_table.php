<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('work_jobs_states')) {
            return;
        }

        Schema::create('work_jobs_states', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('company_id')->index();
            $table->bigInteger('user_id')->index();
            $table->bigInteger('team_id')->nullable()->index();
            $table->bigInteger('created_by_team_id')->nullable()->index();

            $table->unsignedBigInteger('job_id')->index();

            $table->string('state_type', 60)->default('domain'); // domain, compliance, payment, qa
            $table->string('state_key', 80); // e.g. compliance_state, payment_state
            $table->string('status', 40)->default('unknown'); // unknown, ok, warn, fail, pending, done
            $table->string('label', 200)->nullable();
            $table->json('meta_json')->nullable();

            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->unique(['company_id', 'user_id', 'job_id', 'state_key'], 'uq_jobs_states_key');
            $table->index(['company_id', 'user_id', 'status'], 'idx_jobs_states_status');
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('work_jobs_states')) {
            Schema::drop('work_jobs_states');
        }
    }
};
