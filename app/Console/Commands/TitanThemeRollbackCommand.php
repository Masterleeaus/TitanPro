<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\OrganizationBranding;
use App\Models\PlatformSetting;
use App\Models\TitanThemeVersion;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class TitanThemeRollbackCommand extends Command
{
    protected $signature = 'titan:theme:rollback {org} {version} {--panel=global}';

    protected $description = 'Rollback an organisation panel theme to a saved version.';

    public function handle(): int
    {
        if (! Schema::hasTable('titan_theme_versions')) {
            $this->error('titan_theme_versions table not found. Run migrations first.');

            return self::FAILURE;
        }

        $orgId = (int) $this->argument('org');
        $versionNumber = (int) $this->argument('version');
        $panel = (string) $this->option('panel');

        $version = TitanThemeVersion::query()
            ->where('org_id', $orgId)
            ->where('panel', $panel)
            ->where('version_number', $versionNumber)
            ->first();

        if (! $version) {
            $this->error("Theme version v{$versionNumber} not found for org {$orgId} on panel {$panel}.");

            return self::FAILURE;
        }

        $snapshot = is_array($version->token_snapshot) ? $version->token_snapshot : [];
        $branding = OrganizationBranding::query()->firstOrCreate(['organization_id' => $orgId]);

        $branding->fill([
            'panel_name' => $snapshot['panel_name'] ?? null,
            'primary_color' => $snapshot['primary_color'] ?? $branding->primary_color,
            'secondary_color' => $snapshot['secondary_color'] ?? $branding->secondary_color,
            'font_family' => $snapshot['font_family'] ?? $branding->font_family,
            'background_type' => $snapshot['background_type'] ?? $branding->background_type,
            'background_value' => $snapshot['background_value'] ?? $branding->background_value,
            'menu_items' => $snapshot['menu_items'] ?? $branding->menu_items,
            'dashboard_layout' => $snapshot['dashboard_layout'] ?? $branding->dashboard_layout,
        ])->save();

        $settings = PlatformSetting::current();
        $settings->update([
            'accent_color' => $snapshot['accent_color'] ?? $settings->accent_color,
            'surface_color' => $snapshot['surface_color'] ?? $settings->surface_color,
        ]);

        $newVersion = TitanThemeVersion::createSnapshot(
            $orgId,
            $panel,
            $snapshot,
            "Rollback from v{$versionNumber}",
            null
        );

        $this->info("Rollback complete. Created v{$newVersion->version_number} from v{$versionNumber}.");

        return self::SUCCESS;
    }
}
