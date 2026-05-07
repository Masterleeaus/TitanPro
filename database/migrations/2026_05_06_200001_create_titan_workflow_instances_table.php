<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('titan_workflow_instances')) {
            return;
        }

        Schema::create('titan_workflow_instances', function (Blueprint $table) {
            $table->id();

            // Tenant scope
            $table->unsignedBigInteger('company_id')->nullable()->index();

            // Workflow identity
            $table->string('workflow_id', 120)->index(); // slug from manifest
            $table->string('workflow_version', 20)->default('1.0.0');

            // Entity context (optional polymorphic binding)
            $table->string('entity_type', 120)->nullable()->index();
            $table->unsignedBigInteger('entity_id')->nullable()->index();

            // Execution state
            // pending | running | waiting | completed | failed | cancelled
            $table->string('status', 32)->default('pending')->index();

            // Currently active step key
            $table->string('current_step', 120)->nullable();

            // Full context/variables bag passed between steps
            $table->json('context')->nullable();

            // Retry tracking
            $table->unsignedSmallInteger('attempt')->default(0);

            // Actor who initiated the workflow (user_id or "system")
            $table->string('initiated_by', 64)->nullable();

            // Timestamps
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('titan_workflow_instances');
    }
};
