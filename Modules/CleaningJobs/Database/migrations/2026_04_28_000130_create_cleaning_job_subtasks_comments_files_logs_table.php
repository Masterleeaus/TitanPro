<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('cleaning_job_subtasks')) {
            Schema::create('cleaning_job_subtasks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('task_id')->constrained('cleaning_job_tasks')->cascadeOnDelete();
                $table->string('title');
                $table->boolean('is_complete')->default(false);
                $table->unsignedInteger('order')->default(0);
                $table->unsignedBigInteger('completed_by_id')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();
            });
        }
        if (! Schema::hasTable('cleaning_job_comments')) {
            Schema::create('cleaning_job_comments', function (Blueprint $table) {
                $table->id();
                $table->morphs('commentable');
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->text('body');
                $table->boolean('is_internal')->default(false);
                $table->timestamps();
            });
        }
        if (! Schema::hasTable('cleaning_job_files')) {
            Schema::create('cleaning_job_files', function (Blueprint $table) {
                $table->id();
                $table->morphs('fileable');
                $table->string('disk')->default('public');
                $table->string('path');
                $table->string('name');
                $table->string('extension')->nullable();
                $table->unsignedBigInteger('size')->default(0);
                $table->unsignedBigInteger('uploaded_by_id')->nullable()->index();
                $table->timestamps();
            });
        }
        if (! Schema::hasTable('cleaning_job_activity_logs')) {
            Schema::create('cleaning_job_activity_logs', function (Blueprint $table) {
                $table->id();
                $table->morphs('subject');
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->string('event');
                $table->json('properties')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cleaning_job_activity_logs');
        Schema::dropIfExists('cleaning_job_files');
        Schema::dropIfExists('cleaning_job_comments');
        Schema::dropIfExists('cleaning_job_subtasks');
    }
};
