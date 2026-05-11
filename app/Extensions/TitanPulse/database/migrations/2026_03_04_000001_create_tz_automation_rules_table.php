<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tz_automation_rules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('team_id');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();

            $table->string('trigger_type', 40)->default('signal');
            $table->string('trigger_event', 160); // e.g. work.job.completed

            $table->json('conditions_json')->nullable();
            $table->json('actions_json')->nullable();

            $table->boolean('enabled')->default(true);
            $table->timestamps();

            $table->index(['team_id', 'enabled']);
            $table->index(['team_id', 'trigger_event']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tz_automation_rules');
    }
};
