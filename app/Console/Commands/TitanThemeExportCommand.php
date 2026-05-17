<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Support\ThemeExportManager;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class TitanThemeExportCommand extends Command
{
    protected $signature = 'titan:export:theme
        {name : Export base name}
        {format : theme_zip|ui_pack|branding_kit|tenant_preset|css|style_dictionary}
        {--path= : Optional output directory path}';

    protected $description = 'Export Titan UI configuration as theme ZIP, UI pack, branding kit, tenant preset, CSS, or Style Dictionary JSON.';

    public function handle(ThemeExportManager $manager): int
    {
        $name = (string) $this->argument('name');
        $format = (string) $this->argument('format');

        if (! array_key_exists($format, $manager->formats())) {
            $this->error('Unsupported format: '.$format);
            $this->line('Supported formats: '.implode(', ', array_keys($manager->formats())));

            return self::FAILURE;
        }

        try {
            $export = $manager->export($name, $format);
        } catch (\Throwable $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        $directory = $this->option('path');
        if (! is_string($directory) || trim($directory) === '') {
            $directory = storage_path('app/exports');
        }

        if (! is_dir($directory)) {
            if (! mkdir($directory, 0755, true)) {
                $this->error('Failed to create export directory: '.$directory);

                return self::FAILURE;
            }
        }

        $targetPath = rtrim($directory, '/').'/'.Str::slug($name).'-'.$format.'.'.pathinfo($export['fileName'], PATHINFO_EXTENSION);

        if (! rename($export['path'], $targetPath)) {
            // Cross-filesystem moves can fail with rename(), so fallback to copy+delete.
            if (! copy($export['path'], $targetPath)) {
                $this->cleanupTemporaryFile($export['path']);

                $this->error('Unable to copy export artifact from '.$export['path'].' to '.$targetPath.'.');

                return self::FAILURE;
            }

            $this->cleanupTemporaryFile($export['path']);
        }

        $this->info('Theme export generated: '.$targetPath);

        return self::SUCCESS;
    }

    private function cleanupTemporaryFile(string $path): void
    {
        if (file_exists($path) && ! unlink($path)) {
            $this->warn('Temporary export file could not be deleted: '.$path);
        }
    }
}
