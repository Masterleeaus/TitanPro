<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('organization_brandings')) {
            return;
        }

        Schema::table('organization_brandings', function (Blueprint $table): void {
            if (! Schema::hasColumn('organization_brandings', 'installed_theme_packs')) {
                $table->json('installed_theme_packs')->nullable()->default('[]')->after('dashboard_layout');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('organization_brandings')) {
            return;
        }

        Schema::table('organization_brandings', function (Blueprint $table): void {
            if (Schema::hasColumn('organization_brandings', 'installed_theme_packs')) {
                $table->dropColumn('installed_theme_packs');
            }
        });
    }
};
