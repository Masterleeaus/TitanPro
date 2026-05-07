<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('titan_accessibility_reports')) {
            return;
        }

        Schema::create('titan_accessibility_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('platform_setting_id')->nullable()->constrained('platform_settings')->nullOnDelete();
            $table->string('theme')->default('platform');
            $table->json('tokens');
            $table->json('checks');
            $table->json('summary');
            $table->json('dismissed_checks')->nullable();
            $table->json('applied_fixes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('titan_accessibility_reports');
    }
};
