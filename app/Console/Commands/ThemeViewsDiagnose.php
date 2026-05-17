<?php

namespace App\Console\Commands;

use App\Support\ThemeRuntime;
use Illuminate\Console\Command;

class ThemeViewsDiagnose extends Command
{
    protected $signature = 'theme:views {slug? : Theme slug to inspect}';
    protected $description = 'Show active theme view override paths.';

    public function handle(): int
    {
        $slug = $this->argument('slug') ?: ThemeRuntime::activeThemeSlug();

        if (! $slug) {
            $this->warn('No active theme selected.');

            return self::FAILURE;
        }

        $this->line("Theme: {$slug}");

        foreach (ThemeRuntime::viewOverridePaths($slug) as $name => $path) {
            $this->line(($path && is_dir($path) ? '[ok] ' : '[missing] ') . "{$name}: {$path}");
        }

        return self::SUCCESS;
    }
}
