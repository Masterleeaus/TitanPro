<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('budget_forecast_scenarios', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->string('name');
            $table->date('period_start');
            $table->date('period_end');
            $table->string('model_version')->nullable();
            $table->json('scenario_data')->nullable();
            $table->enum('status', ['draft', 'running', 'ready', 'failed'])->default('draft');
            $table->timestamp('generated_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budget_forecast_scenarios');
    }
};
