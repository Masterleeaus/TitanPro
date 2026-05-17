<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fsm_service_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->string('name');
            $table->string('code', 50)->nullable();
            $table->decimal('default_rate', 12, 2)->default(0);
            $table->string('unit', 30)->nullable();
            $table->timestamps();
        });

        Schema::create('field_job_service_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedBigInteger('field_job_id');
            $table->unsignedBigInteger('service_task_id')->nullable();
            $table->string('task_name')->nullable();
            $table->decimal('qty', 12, 4)->default(1);
            $table->decimal('rate', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->timestamps();

            $table->index(['company_id', 'field_job_id']);
            $table->foreign('field_job_id')->references('id')->on('field_jobs')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('field_job_service_tasks');
        Schema::dropIfExists('fsm_service_tasks');
    }
};
