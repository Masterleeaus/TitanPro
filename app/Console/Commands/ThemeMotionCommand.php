<?php

namespace App\Console\Commands;

use App\Support\ThemeMotionManager;
use Illuminate\Console\Command;

class ThemeMotionCommand extends Command
{
    protected $signature = 'theme:motion {preset? : Motion preset} {--list : List motion presets}';
    protected $description = 'List or set Theme Manager motion presets.';

    public function handle(): int
    {
        if ($this->option('list') || ! $this->argument('preset')) {
            $this->line('Active motion: ' . ThemeMotionManager::active());

            foreach (ThemeMotionManager::options() as $slug => $label) {
                $this->line(" - {$slug}: {$label}");
            }

            return self::SUCCESS;
        }

        return ThemeMotionManager::set((string) $this->argument('preset'))
            ? self::SUCCESS
            : self::FAILURE;
    }
}
