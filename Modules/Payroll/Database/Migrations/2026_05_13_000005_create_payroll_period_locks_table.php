<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('payroll_period_locks')) {
            Schema::create('payroll_period_locks', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('company_id')->index();
                $table->date('period_from');
                $table->date('period_to');
                $table->string('status')->default('locked')->index();
                $table->unsignedBigInteger('locked_by')->nullable()->index();
                $table->timestamp('locked_at')->nullable();
                $table->unsignedBigInteger('unlocked_by')->nullable()->index();
                $table->timestamp('unlocked_at')->nullable();
                $table->text('unlock_reason')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();
                $table->unique(['company_id', 'period_from', 'period_to'], 'payroll_period_locks_unique');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_period_locks');
    }
};
