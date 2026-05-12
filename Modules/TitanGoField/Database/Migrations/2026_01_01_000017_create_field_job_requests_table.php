<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('field_job_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedBigInteger('field_job_id')->nullable();
            $table->unsignedBigInteger('requested_by_id')->nullable();
            $table->string('channel', 50)->default('web');
            $table->text('description')->nullable();
            $table->string('status', 50)->default('pending');
            $table->timestamps();

            $table->index(['company_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('field_job_requests');
    }
};
