<?php

namespace App\Console\Commands;

use App\Support\ThemeRuntime;
use Illuminate\Console\Command;

class ThemeSetActive extends Command
{
    protected $signature = 'theme:set-active {slug : Installed theme slug} {--clear : Clear active theme instead}';
    protected $description = 'Set or clear the active filesystem theme used by Theme Manager.';

    public function handle(): int
    {
        if ($this->option('clear')) {
            ThemeRuntime::clearActiveTheme();
            $this->info('Active theme cleared.');

            return self::SUCCESS;
        }

        $slug = (string) $this->argument('slug');

        if (! ThemeRuntime::setActiveThemeSlug($slug)) {
            $this->error("Theme [{$slug}] is not installed.");
            $this->line('Installed themes: ' . implode(', ', ThemeRuntime::installedThemes()));

            return self::FAILURE;
        }

        $this->info("Active theme set to [{$slug}].");

        return self::SUCCESS;
    }
}
