<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Scans module directories for files that must not be present in production.
 *
 * Detected patterns (BLOCKER-level):
 *   *.stub  *.demo  *.scaffold  *.example
 *
 * Exit codes:
 *   0 — no blockers found
 *   1 — one or more BLOCKER files detected
 */
class ModulesScanProductionCommand extends Command
{
    protected $signature = 'modules:scan-production
                            {--json  : Output results as JSON}
                            {--module= : Scan a single named module}';

    protected $description = 'Scan module directories for production-blocker files.';

    /** File extensions that signal scaffold / demo content. */
    private const BLOCKER_EXTENSIONS = ['stub', 'demo', 'scaffold', 'example'];

    public function handle(): int
    {
        $modulesBase = base_path('Modules');

        if (! is_dir($modulesBase)) {
            $this->error('Modules directory not found: '.$modulesBase);

            return self::FAILURE;
        }

        $only = $this->option('module');
        $dirs = File::directories($modulesBase);
        $blockers = [];

        foreach ($dirs as $dir) {
            $name = basename($dir);

            if ($only && $name !== $only) {
                continue;
            }

            $found = $this->scanModule($dir);

            if (! empty($found)) {
                $blockers[$name] = $found;
            }
        }

        if ($this->option('json')) {
            $this->line(json_encode([
                'status' => empty($blockers) ? 'clean' : 'blockers_found',
                'blockers' => $blockers,
            ], JSON_PRETTY_PRINT));
        } else {
            $this->printReport($blockers);
        }

        return empty($blockers) ? self::SUCCESS : self::FAILURE;
    }

    /**
     * Return all BLOCKER files found under $dir.
     *
     * @return list<string>
     */
    private function scanModule(string $dir): array
    {
        $found = [];

        $pattern = implode(',', array_map(
            fn (string $ext) => "*.{$ext}",
            self::BLOCKER_EXTENSIONS
        ));

        // Use RecursiveDirectoryIterator for reliable deep scanning.
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if (! $file->isFile()) {
                continue;
            }

            $ext = strtolower(pathinfo($file->getPathname(), PATHINFO_EXTENSION));

            if (in_array($ext, self::BLOCKER_EXTENSIONS, true)) {
                // Return path relative to the Modules directory
                $found[] = str_replace(base_path('Modules').'/', '', $file->getPathname());
            }
        }

        sort($found);

        return $found;
    }

    private function printReport(array $blockers): void
    {
        if (empty($blockers)) {
            $this->info('✓  No production-blocker files detected.');

            return;
        }

        $this->error('BLOCKER files detected — these must not be present in production:');
        $this->line('');

        foreach ($blockers as $module => $files) {
            $this->line("<comment>[{$module}]</comment>");

            foreach ($files as $file) {
                $this->line("  <error>BLOCKER</error>  {$file}");
            }

            $this->line('');
        }

        $total = array_sum(array_map('count', $blockers));
        $this->error("Total: {$total} BLOCKER file(s) in ".count($blockers).' module(s).');
    }
}
