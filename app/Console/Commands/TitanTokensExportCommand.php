<?php

namespace App\Console\Commands;

use App\Models\PlatformSetting;
use App\Support\ThemeTokenManager;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class TitanTokensExportCommand extends Command
{
    protected $signature = 'titan:tokens:export {--panel=} {--path=}';
    protected $description = 'Export Titan design tokens as CSS, Style Dictionary JSON, and Tailwind config.';

    public function handle(ThemeTokenManager $manager, Filesystem $files): int
    {
        $settings = PlatformSetting::current();
        $panel = $this->option('panel');
        $directory = $this->option('path') ?: storage_path('app/exports/titan-theme-tokens');
        $exports = $manager->exportPayload($settings, is_string($panel) && $panel !== '' ? $panel : null);

        $files->ensureDirectoryExists($directory);
        $files->put($directory.'/theme.css', $exports['css'].PHP_EOL);
        $files->put($directory.'/style-dictionary.json', json_encode($exports['json'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES).PHP_EOL);
        $files->put($directory.'/tailwind.tokens.js', $exports['tailwind']);

        $this->info('Exported Titan theme tokens to '.$directory);

        return self::SUCCESS;
    }
}
