<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('budget_integration_runs', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->enum('source', ['accounting', 'banking', 'payroll', 'erp']);
            $table->enum('status', ['pending', 'running', 'done', 'failed'])->default('pending');
            $table->integer('records_synced')->default(0);
            $table->text('error_message')->nullable();
            $table->timestamp('ran_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budget_integration_runs');
    }
};
