<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('platform_settings')) {
            return;
        }

        Schema::table('platform_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('platform_settings', 'surface_color')) {
                $table->string('surface_color')->nullable()->after('accent_color');
            }

            if (! Schema::hasColumn('platform_settings', 'font_heading')) {
                $table->string('font_heading')->nullable()->after('surface_color');
            }

            if (! Schema::hasColumn('platform_settings', 'font_body')) {
                $table->string('font_body')->nullable()->after('font_heading');
            }

            if (! Schema::hasColumn('platform_settings', 'font_source_url')) {
                $table->string('font_source_url', 2048)->nullable()->after('font_body');
            }

            if (! Schema::hasColumn('platform_settings', 'font_path')) {
                $table->string('font_path')->nullable()->after('font_source_url');
            }

            if (! Schema::hasColumn('platform_settings', 'bg_image_path')) {
                $table->string('bg_image_path')->nullable()->after('font_path');
            }

            if (! Schema::hasColumn('platform_settings', 'theme_snapshots')) {
                $table->json('theme_snapshots')->nullable()->after('bg_image_path');
            }
        });
    }

    public function down(): void
    {
        // Non-destructive rollback: keep branding snapshots and generated theme values.
    }
};
