<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('tz_portal_operator_workflow_runs')) {
            return;
        }

        Schema::create('tz_portal_operator_workflow_runs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('operator_id');
            $table->unsignedBigInteger('conversation_id')->nullable();
            $table->string('workflow_key', 100);
            $table->string('status', 32)->default('proposed'); // proposed|confirmed|executing|completed|failed|canceled
            $table->json('input')->nullable();
            $table->json('result')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('executed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['operator_id', 'workflow_key'], 'tz_portal_operator_workflow_runs_operator_workflow');
            $table->index(['conversation_id'], 'tz_portal_operator_workflow_runs_conversation');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tz_portal_operator_workflow_runs');
    }
};
