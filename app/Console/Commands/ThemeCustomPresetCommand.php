<?php

namespace App\Console\Commands;

use App\Support\ThemeCustomPresetStore;
use Illuminate\Console\Command;

class ThemeCustomPresetCommand extends Command
{
    protected $signature = 'theme:custom-preset {action : list|duplicate|delete|export|import} {value?} {--label=}';
    protected $description = 'Manage custom Theme Manager presets.';

    public function handle(): int
    {
        $action = (string) $this->argument('action');
        $value = $this->argument('value');

        if ($action === 'list') {
            foreach (ThemeCustomPresetStore::all() as $slug => $preset) {
                $this->line($slug . ': ' . ($preset['label'] ?? $slug));
            }
            return self::SUCCESS;
        }

        if ($action === 'duplicate') {
            $slug = ThemeCustomPresetStore::duplicate((string) $value, $this->option('label'));
            $slug ? $this->info("Duplicated preset: {$slug}") : $this->error('Unable to duplicate preset.');
            return $slug ? self::SUCCESS : self::FAILURE;
        }

        if ($action === 'delete') {
            return ThemeCustomPresetStore::delete((string) $value) ? self::SUCCESS : self::FAILURE;
        }

        if ($action === 'export') {
            $this->info('Exported: ' . ThemeCustomPresetStore::export((string) $value ?: null));
            return self::SUCCESS;
        }

        if ($action === 'import') {
            $result = ThemeCustomPresetStore::import((string) $value);
            $this->info('Imported presets: ' . ($result['imported'] ?? 0));
            return ($result['ok'] ?? false) ? self::SUCCESS : self::FAILURE;
        }

        return self::FAILURE;
    }
}
