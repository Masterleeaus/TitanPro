<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('titan_usage_meters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('meter_key', 100); // e.g. 'cleaning_jobs', 'voice_seconds'
            $table->string('period', 7);      // e.g. '2026-05' (YYYY-MM)
            $table->unsignedBigInteger('count')->default(0);
            $table->timestamp('reset_at')->nullable();
            $table->timestamps();

            $table->unique(['organization_id', 'meter_key', 'period']);
            $table->index(['organization_id', 'meter_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('titan_usage_meters');
    }
};
