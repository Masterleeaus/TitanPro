<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\PlatformSetting;
use App\Models\OrganizationBranding;
use App\Support\ThemePackManager;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

/**
 * Export the currently active theme (or a named built-in theme) as a
 * redistributable ZIP pack.
 *
 * Usage:
 *   php artisan titan:theme:export                      # exports current active theme
 *   php artisan titan:theme:export "My Brand"           # exports with custom name
 *   php artisan titan:theme:export ocean --builtin      # exports a built-in theme by key
 *   php artisan titan:theme:export --out=/tmp/my.zip    # custom output path
 */
class ExportThemeCommand extends Command
{
    protected $signature = 'titan:theme:export
        {name? : Theme name (defaults to current platform brand name)}
        {--builtin : Export a built-in theme by its key instead of active settings}
        {--out= : Absolute path for the output ZIP (defaults to storage/app/exports/<name>.zip)}';

    protected $description = 'Export the active theme (or a built-in theme) as an installable theme pack ZIP.';

    public function handle(ThemePackManager $manager): int
    {
        $name = $this->argument('name');

        if ($this->option('builtin')) {
            return $this->exportBuiltin((string) ($name ?? 'ocean'), $manager);
        }

        return $this->exportActive((string) ($name ?: null), $manager);
    }

    // ─────────────────────────────────────────────────────────────────────────

    private function exportBuiltin(string $key, ThemePackManager $manager): int
    {
        $themes = ThemePackManager::builtinThemes();

        if (! isset($themes[$key])) {
            $this->error("Unknown built-in theme key: \"{$key}\".");
            $this->line('Available keys: ' . implode(', ', array_keys($themes)));

            return self::FAILURE;
        }

        $theme  = $themes[$key];
        $tokens = $theme['tokens'];
        $name   = $theme['name'];

        return $this->writeZip($name, $tokens, $manager);
    }

    private function exportActive(?string $nameOverride, ThemePackManager $manager): int
    {
        $settings = PlatformSetting::current();

        $tokens = array_filter([
            'primary_color'   => $settings->primary_color,
            'secondary_color' => $settings->secondary_color,
            'accent_color'    => $settings->accent_color,
            'surface_color'   => $settings->surface_color,
            'font_heading'    => $settings->font_heading,
            'font_body'       => $settings->font_body,
        ]);

        $name = $nameOverride ?: $settings->brandName();

        return $this->writeZip($name, $tokens, $manager);
    }

    private function writeZip(string $name, array $tokens, ThemePackManager $manager): int
    {
        try {
            $tmpPath = $manager->buildExportZip($name, $tokens);
        } catch (\RuntimeException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $outPath = $this->option('out');
        if (! $outPath) {
            $slug    = Str::slug($name);
            $dir     = storage_path('app/exports');
            if (! is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            $outPath = $dir . '/' . $slug . '.zip';
        }

        if (! rename($tmpPath, $outPath)) {
            copy($tmpPath, $outPath);
            @unlink($tmpPath);
        }

        $this->info("Theme pack exported to: {$outPath}");

        return self::SUCCESS;
    }
}
