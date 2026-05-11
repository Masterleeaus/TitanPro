<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('titan_ui_component_overrides')) {
            return;
        }

        Schema::create('titan_ui_component_overrides', function (Blueprint $table) {
            $table->id();

            // Which component this override / preset belongs to (e.g. "stat-card").
            $table->string('component', 64)->index();

            // Which Filament panel the override applies to (e.g. "admin", "titanpro").
            // NULL rows are "platform-wide" overrides that are not scoped to any
            // specific panel (used when componentPanel is left blank).
            $table->string('panel', 64)->nullable()->index();

            // Which tenant this override belongs to. Null means platform-wide.
            $table->unsignedBigInteger('organization_id')->nullable()->index();

            // The design token key (e.g. "background", "border-radius").
            $table->string('token_key', 128);

            // The token value (e.g. "#2563eb", "0.75rem").
            $table->string('value', 512);

            // When set this row belongs to a named preset rather than being an
            // active override.  Active overrides have preset_name = null.
            $table->string('preset_name', 128)->nullable()->index();

            $table->timestamps();

            // One active override per (component, panel, org, token).
            // One preset row per (component, panel, org, preset_name, token).
            $table->unique(
                ['component', 'panel', 'organization_id', 'token_key', 'preset_name'],
                'ui_overrides_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('titan_ui_component_overrides');
    }
};
