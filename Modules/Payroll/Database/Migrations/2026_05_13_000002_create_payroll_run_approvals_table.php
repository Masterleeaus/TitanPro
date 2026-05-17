<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payroll_run_approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payroll_run_id')->index();
            $table->unsignedBigInteger('approver_id')->nullable()->index();
            $table->string('step')->default('manager_review');
            $table->string('status')->default('pending')->index();
            $table->text('comment')->nullable();
            $table->timestamp('acted_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->unique(['payroll_run_id', 'step'], 'payroll_run_approval_step_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_run_approvals');
    }
};
