<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('cleaning_job_stages')) {
            Schema::create('cleaning_job_stages', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('type')->default('job');
                $table->unsignedInteger('order')->default(0);
                $table->string('color')->nullable();
                $table->boolean('is_default')->default(false);
                $table->boolean('is_completed')->default(false);
                $table->boolean('is_active')->default(true);
                $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cleaning_job_stages');
    }
};
