<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('cleaning_job_timesheets')) {
            Schema::create('cleaning_job_timesheets', function (Blueprint $table) {
                $table->id();
                $table->foreignId('work_order_id')->constrained('work_orders')->cascadeOnDelete();
                $table->foreignId('task_id')->nullable()->constrained('cleaning_job_tasks')->nullOnDelete();
                $table->unsignedBigInteger('user_id')->index();
                $table->date('work_date');
                $table->decimal('hours', 8, 2);
                $table->text('description')->nullable();
                $table->boolean('is_billable')->default(true);
                $table->decimal('billing_rate', 12, 2)->default(0);
                $table->decimal('cost_rate', 12, 2)->default(0);
                $table->decimal('billable_amount', 12, 2)->default(0);
                $table->decimal('cost_amount', 12, 2)->default(0);
                $table->string('status')->default('draft');
                $table->unsignedBigInteger('approved_by_id')->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->timestamps();
                $table->softDeletes();
                $table->index(['work_order_id', 'work_date']);
                $table->index(['user_id', 'work_date']);
            });
        }
        if (! Schema::hasTable('cleaning_job_resource_allocations')) {
            Schema::create('cleaning_job_resource_allocations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('work_order_id')->constrained('work_orders')->cascadeOnDelete();
                $table->foreignId('task_id')->nullable()->constrained('cleaning_job_tasks')->nullOnDelete();
                $table->unsignedBigInteger('user_id')->index();
                $table->date('start_date');
                $table->date('end_date')->nullable();
                $table->decimal('allocation_percentage', 5, 2)->default(100);
                $table->decimal('hours_per_day', 4, 2)->default(8);
                $table->string('allocation_type')->default('job');
                $table->text('notes')->nullable();
                $table->boolean('is_billable')->default(true);
                $table->boolean('is_confirmed')->default(false);
                $table->string('status')->default('planned');
                $table->unsignedBigInteger('created_by_id')->nullable();
                $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->timestamps();
                $table->softDeletes();
                $table->index(['user_id', 'start_date', 'end_date']);
            });
        }
        if (! Schema::hasTable('cleaning_job_resource_capacities')) {
            Schema::create('cleaning_job_resource_capacities', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->index();
                $table->date('date');
                $table->decimal('available_hours', 4, 2)->default(8);
                $table->decimal('allocated_hours', 4, 2)->default(0);
                $table->decimal('utilized_hours', 4, 2)->default(0);
                $table->boolean('is_working_day')->default(true);
                $table->string('leave_type')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->unique(['user_id', 'date']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cleaning_job_resource_capacities');
        Schema::dropIfExists('cleaning_job_resource_allocations');
        Schema::dropIfExists('cleaning_job_timesheets');
    }
};
