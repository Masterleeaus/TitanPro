<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ground_zero_incidents')) {
            return;
        }

        Schema::create('ground_zero_incidents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('job_id')->nullable();
            $table->unsignedBigInteger('reported_by');
            $table->string('severity', 30)->default('medium');
            $table->string('status', 50)->default('open');
            $table->json('details')->nullable();
            $table->timestamp('logged_at')->nullable();
            $table->timestamps();

            $table->index('company_id');
            $table->index(['company_id', 'status']);
            $table->index(['company_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ground_zero_incidents');
    }
};
