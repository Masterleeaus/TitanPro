<?php

namespace App\Console\Commands;

use App\Support\ThemePresetManager;
use Illuminate\Console\Command;

class ThemePresetCommand extends Command
{
    protected $signature = 'theme:preset {preset? : Preset slug} {--list : List presets} {--json : Output JSON}';
    protected $description = 'List or apply Theme Manager presets.';

    public function handle(): int
    {
        if ($this->option('json')) {
            $this->line(json_encode(ThemePresetManager::diagnostics(), JSON_PRETTY_PRINT));

            return self::SUCCESS;
        }

        if ($this->option('list') || ! $this->argument('preset')) {
            $this->line('Active preset: ' . ThemePresetManager::activePresetSlug());

            foreach (ThemePresetManager::gallery() as $preset) {
                $active = $preset['active'] ? ' [active]' : '';
                $this->line(" - {$preset['slug']}: {$preset['label']}{$active}");
            }

            return self::SUCCESS;
        }

        $preset = (string) $this->argument('preset');

        if (! ThemePresetManager::setActivePreset($preset)) {
            $this->error("Invalid preset: {$preset}");

            return self::FAILURE;
        }

        $this->info("Theme preset set to {$preset}");

        return self::SUCCESS;
    }
}
