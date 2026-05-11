<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('tz_portal_operator_workflow_settings')) {
            return;
        }

        Schema::create('tz_portal_operator_workflow_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('operator_id');
            $table->string('workflow_key', 100);
            $table->boolean('enabled')->default(true);
            $table->timestamps();

            $table->unique(['operator_id', 'workflow_key'], 'tz_portal_operator_workflow_settings_unique');
            $table->index(['operator_id'], 'tz_portal_operator_workflow_settings_operator_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tz_portal_operator_workflow_settings');
    }
};
