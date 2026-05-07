<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('titan_workflow_audit')) {
            return;
        }

        Schema::create('titan_workflow_audit', function (Blueprint $table) {
            $table->id();

            // Parent workflow instance
            $table->unsignedBigInteger('workflow_instance_id')->index();

            // Tenant scope
            $table->unsignedBigInteger('company_id')->nullable()->index();

            // Step that was executed
            $table->string('step_key', 120);

            // Step type: action | condition | wait | parallel
            $table->string('step_type', 32)->nullable();

            // Transition outcome: started | completed | failed | skipped | guarded
            $table->string('outcome', 32)->index();

            // Actor: user_id (string) or "system"
            $table->string('actor', 64)->nullable();

            // Optional payload snapshot for the step
            $table->json('payload')->nullable();

            // Error message if failed
            $table->text('error')->nullable();

            // Duration in milliseconds
            $table->unsignedInteger('duration_ms')->nullable();

            // Append-only — no updated_at
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('titan_workflow_audit');
    }
};
