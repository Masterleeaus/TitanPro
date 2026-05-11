<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ui_overrides', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedBigInteger('organization_id')->nullable()->index();
            // Unique key for the component being overridden, e.g. "filament-widget-revenue-chart"
            $table->string('component_key', 255);
            // JSON blob of CSS property overrides: { "--padding": "1rem", "border-radius": "0.5rem", ... }
            $table->json('properties');
            $table->timestamps();

            $table->unique(['component_key', 'organization_id'], 'ui_overrides_key_org_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ui_overrides');
    }
};
