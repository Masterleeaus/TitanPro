<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('field_job_assets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedBigInteger('field_job_id');
            $table->unsignedBigInteger('asset_id');
            $table->string('note')->nullable();
            $table->timestamps();

            $table->index(['company_id', 'field_job_id']);
            $table->foreign('field_job_id')->references('id')->on('field_jobs')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('field_job_assets');
    }
};
