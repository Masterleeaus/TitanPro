<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('tz_portal_operator_tool_settings')) {
            return;
        }

        Schema::create('tz_portal_operator_tool_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('operator_id');
            $table->string('tool_key', 100);
            $table->boolean('enabled')->default(true);
            $table->timestamps();

            $table->index(['operator_id', 'tool_key'], 'tz_portal_operator_tool_settings_operator_tool_idx');
            $table->unique(['operator_id', 'tool_key'], 'tz_portal_operator_tool_settings_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tz_portal_operator_tool_settings');
    }
};
