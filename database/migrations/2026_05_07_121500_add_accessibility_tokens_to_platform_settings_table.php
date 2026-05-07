<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('platform_settings')) {
            return;
        }

        Schema::table('platform_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('platform_settings', 'background_color')) {
                $table->string('background_color')->nullable()->after('accent_color');
            }

            if (! Schema::hasColumn('platform_settings', 'button_text_color')) {
                $table->string('button_text_color')->nullable()->after('background_color');
            }

            if (! Schema::hasColumn('platform_settings', 'focus_ring_color')) {
                $table->string('focus_ring_color')->nullable()->after('button_text_color');
            }

            if (! Schema::hasColumn('platform_settings', 'font_scale')) {
                $table->decimal('font_scale', 3, 1)->default(1.0)->after('focus_ring_color');
            }

            if (! Schema::hasColumn('platform_settings', 'accessibility_dismissals')) {
                $table->json('accessibility_dismissals')->nullable()->after('font_scale');
            }
        });

        DB::table('platform_settings')->update([
            'background_color' => DB::raw("COALESCE(background_color, '#ffffff')"),
            'button_text_color' => DB::raw("COALESCE(button_text_color, '#ffffff')"),
            'focus_ring_color' => DB::raw('COALESCE(focus_ring_color, primary_color, \'#2563eb\')'),
            'font_scale' => DB::raw('COALESCE(font_scale, 1.0)'),
            'accessibility_dismissals' => DB::raw("COALESCE(accessibility_dismissals, '[]')"),
        ]);
    }

    public function down(): void
    {
        // Non-destructive rollback: keep accessibility token data intact.
    }
};
