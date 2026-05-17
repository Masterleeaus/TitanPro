<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('budget_variances', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedBigInteger('actual_id')->constrained('budget_actuals')->cascadeOnDelete();
            $table->decimal('variance_amount', 15, 2);
            $table->decimal('variance_pct', 8, 4)->default(0);
            $table->enum('flag', ['normal', 'warning', 'critical'])->default('normal');
            $table->text('notes')->nullable();
            $table->boolean('anomaly_flagged')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budget_variances');
    }
};
