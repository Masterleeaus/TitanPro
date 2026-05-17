<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payroll_runs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->string('run_number')->nullable()->index();
            $table->date('period_start')->index();
            $table->date('period_end')->index();
            $table->string('status')->default('draft')->index();
            $table->unsignedInteger('employee_count')->default(0);
            $table->decimal('gross_total', 16, 2)->default(0);
            $table->decimal('deduction_total', 16, 2)->default(0);
            $table->decimal('net_total', 16, 2)->default(0);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->unique(['company_id', 'period_start', 'period_end', 'run_number'], 'payroll_runs_period_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_runs');
    }
};
