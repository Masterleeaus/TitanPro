<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('cleaning_job_tasks')) {
            Schema::create('cleaning_job_tasks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('work_order_id')->constrained('work_orders')->cascadeOnDelete();
                $table->foreignId('stage_id')->nullable()->constrained('cleaning_job_stages')->nullOnDelete();
                $table->foreignId('milestone_id')->nullable()->constrained('cleaning_job_milestones')->nullOnDelete();
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('priority')->default('medium');
                $table->string('status')->default('todo');
                $table->dateTime('start_date')->nullable();
                $table->dateTime('due_date')->nullable();
                $table->string('assigned_to')->nullable();
                $table->unsignedInteger('order')->default(0);
                $table->decimal('estimated_hours', 8, 2)->default(0);
                $table->decimal('actual_hours', 8, 2)->default(0);
                $table->boolean('is_billable')->default(true);
                $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->timestamps();
                $table->softDeletes();
                $table->index(['work_order_id', 'status']);
                $table->index(['stage_id', 'order']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cleaning_job_tasks');
    }
};
