<?php

namespace App\Console\Commands;

use App\Support\ThemeRuntime;
use Illuminate\Console\Command;

class ThemeDiagnose extends Command
{
    protected $signature = 'theme:diagnose {slug? : Theme slug to inspect}';
    protected $description = 'Diagnose Theme Manager runtime state and installed theme assets.';

    public function handle(): int
    {
        $slug = $this->argument('slug') ?: ThemeRuntime::activeThemeSlug();
        $diagnostics = ThemeRuntime::diagnostics($slug);

        $this->line('Active theme: ' . ($diagnostics['active'] ?: 'none'));
        $this->line('Themes path: ' . $diagnostics['themes_path']);
        $this->line('Installed: ' . implode(', ', $diagnostics['installed']));

        $this->newLine();
        $this->line('Assets:');
        foreach ($diagnostics['assets'] as $asset) {
            $this->line(' - ' . $asset);
        }

        $this->newLine();
        $this->line('Issues:');
        foreach ($diagnostics['issues'] ?: ['none'] as $issue) {
            $this->line(' - ' . $issue);
        }

        return self::SUCCESS;
    }
}
