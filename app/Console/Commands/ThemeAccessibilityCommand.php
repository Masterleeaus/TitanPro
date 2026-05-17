<?php

namespace App\Console\Commands;

use App\Support\ThemeAccessibilityEngine;
use App\Support\ThemePresetManager;
use Illuminate\Console\Command;

class ThemeAccessibilityCommand extends Command
{
    protected $signature = 'theme:accessibility';
    protected $description = 'Audit active theme preset accessibility.';

    public function handle(): int
    {
        $preset = class_exists(ThemePresetManager::class) ? ThemePresetManager::activePreset() : [];
        $audit = ThemeAccessibilityEngine::audit($preset['colors'] ?? []);

        foreach ($audit['checks'] as $name => $ratio) {
            $this->line("{$name}: {$ratio}");
        }

        foreach ($audit['issues'] as $issue) {
            $this->error($issue);
        }

        return $audit['ok'] ? self::SUCCESS : self::FAILURE;
    }
}
