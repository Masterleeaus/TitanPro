<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Comprehensive pre-deploy production readiness gate for all Titan modules.
 *
 * Checks performed per module (BLOCKER = fails CI, WARN = advisory only):
 *   1. [BLOCKER] Manifest schema validation  — module.json present, parseable, has required fields
 *   2. [BLOCKER] Scaffold / demo file scan   — no *.stub, *.demo, *.scaffold, *.example files
 *   3. [BLOCKER] Required env vars           — all keys in required_env are set
 *   4. [BLOCKER] Provider registration       — every provider in the manifest exists in bootstrap/providers.php
 *
 * Usage:
 *   php artisan modules:production-check
 *   php artisan modules:production-check --module=CRMCore
 *   php artisan modules:production-check --json
 *
 * Exit codes:
 *   0 — all checks passed (or only warnings)
 *   1 — one or more BLOCKER-level issues found
 */
class ModulesProductionCheckCommand extends Command
{
    protected $signature = 'modules:production-check
                            {--module= : Check a single named module}
                            {--json    : Output results as JSON for machine-readable CI reporting}';

    protected $description = 'Run production readiness checks for all Titan modules (CI deployment gate).';

    /** File extensions that signal scaffold / demo content — BLOCKER level. */
    private const BLOCKER_EXTENSIONS = ['stub', 'demo', 'scaffold', 'example'];

    /** Manifest fields required at BLOCKER level. */
    private const REQUIRED_MANIFEST_FIELDS = ['name', 'providers'];

    /** @var array<string, array<string, mixed>> */
    private array $results = [];

    /** Cached contents of bootstrap/providers.php as a string. */
    private string $bootstrapProvidersContent = '';

    public function handle(): int
    {
        $modulesBase = base_path('Modules');

        if (! is_dir($modulesBase)) {
            $this->error('Modules directory not found: '.$modulesBase);

            return self::FAILURE;
        }

        $this->bootstrapProvidersContent = $this->loadBootstrapProviders();

        $only = $this->option('module');
        $dirs = File::directories($modulesBase);

        foreach ($dirs as $dir) {
            $name = basename($dir);

            if ($only && $name !== $only) {
                continue;
            }

            $this->results[$name] = $this->checkModule($dir, $name);
        }

        if ($this->option('json')) {
            $this->line(json_encode($this->buildJsonReport(), JSON_PRETTY_PRINT));
        } else {
            $this->printReport();
        }

        $hasBlocker = collect($this->results)->contains(
            fn (array $r) => $r['has_blocker'] === true
        );

        return $hasBlocker ? self::FAILURE : self::SUCCESS;
    }

    // ── Per-module check orchestration ────────────────────────────────────────

    /**
     * Run all production checks for one module directory.
     *
     * @return array{has_blocker: bool, checks: array<string, array{level: string, status: string, message: string}>}
     */
    private function checkModule(string $dir, string $name): array
    {
        $checks = [];
        $manifest = null;

        // 1. Manifest schema validation
        [$manifestCheck, $manifest] = $this->checkManifestSchema($dir);
        $checks['manifest_schema'] = $manifestCheck;

        // 2. Scaffold / demo file scan
        $checks['scaffold_files'] = $this->checkScaffoldFiles($dir);

        // 3. Required env vars
        $checks['required_env'] = $this->checkRequiredEnv($manifest);

        // 4. Provider registration in bootstrap/providers.php
        $checks['provider_registration'] = $this->checkProviderRegistration($manifest);

        $hasBlocker = collect($checks)->contains(
            fn (array $c) => $c['level'] === 'BLOCKER' && $c['status'] === 'FAIL'
        );

        return ['has_blocker' => $hasBlocker, 'checks' => $checks];
    }

    // ── Individual checks ──────────────────────────────────────────────────────

    /**
     * Validate the module.json manifest: present, valid JSON, required fields.
     *
     * @return array{0: array{level: string, status: string, message: string}, 1: array<string, mixed>|null}
     */
    private function checkManifestSchema(string $dir): array
    {
        $manifestPath = $dir.'/module.json';

        if (! file_exists($manifestPath)) {
            return [
                ['level' => 'BLOCKER', 'status' => 'FAIL', 'message' => 'module.json not found'],
                null,
            ];
        }

        $contents = file_get_contents($manifestPath);

        if ($contents === false) {
            return [
                ['level' => 'BLOCKER', 'status' => 'FAIL', 'message' => 'module.json could not be read (permissions?)'],
                null,
            ];
        }

        $decoded = json_decode($contents, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                ['level' => 'BLOCKER', 'status' => 'FAIL', 'message' => 'Invalid JSON: '.json_last_error_msg()],
                null,
            ];
        }

        $missing = array_filter(
            self::REQUIRED_MANIFEST_FIELDS,
            fn (string $field) => empty($decoded[$field])
        );

        if (! empty($missing)) {
            return [
                ['level' => 'BLOCKER', 'status' => 'FAIL', 'message' => 'Missing required fields: '.implode(', ', $missing)],
                $decoded,
            ];
        }

        return [
            ['level' => 'BLOCKER', 'status' => 'PASS', 'message' => 'OK'],
            $decoded,
        ];
    }

    /**
     * Scan the module directory for scaffold / demo files (BLOCKER).
     *
     * @return array{level: string, status: string, message: string}
     */
    private function checkScaffoldFiles(string $dir): array
    {
        $found = [];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if (! $file->isFile()) {
                continue;
            }

            $ext = strtolower(pathinfo($file->getPathname(), PATHINFO_EXTENSION));

            if (in_array($ext, self::BLOCKER_EXTENSIONS, true)) {
                $found[] = str_replace(base_path('Modules').'/', '', $file->getPathname());
            }
        }

        sort($found);

        if (empty($found)) {
            return ['level' => 'BLOCKER', 'status' => 'PASS', 'message' => 'OK'];
        }

        return [
            'level'   => 'BLOCKER',
            'status'  => 'FAIL',
            'message' => 'Scaffold/demo files present: '.implode(', ', $found),
        ];
    }

    /**
     * Verify all required_env keys from the manifest are present in the environment.
     *
     * @param  array<string, mixed>|null  $manifest
     * @return array{level: string, status: string, message: string}
     */
    private function checkRequiredEnv(?array $manifest): array
    {
        if ($manifest === null) {
            return ['level' => 'BLOCKER', 'status' => 'WARN', 'message' => 'Skipped — no valid manifest'];
        }

        $required = $manifest['required_env'] ?? [];

        if (empty($required)) {
            return ['level' => 'BLOCKER', 'status' => 'PASS', 'message' => 'None declared'];
        }

        $missing = array_values(array_filter($required, fn ($key) => env($key) === null));

        if (empty($missing)) {
            return ['level' => 'BLOCKER', 'status' => 'PASS', 'message' => 'OK'];
        }

        return [
            'level'   => 'BLOCKER',
            'status'  => 'FAIL',
            'message' => 'Missing env vars: '.implode(', ', $missing),
        ];
    }

    /**
     * Verify every provider declared in the manifest is registered in bootstrap/providers.php.
     *
     * @param  array<string, mixed>|null  $manifest
     * @return array{level: string, status: string, message: string}
     */
    private function checkProviderRegistration(?array $manifest): array
    {
        if ($manifest === null) {
            return ['level' => 'BLOCKER', 'status' => 'WARN', 'message' => 'Skipped — no valid manifest'];
        }

        $providers = $manifest['providers'] ?? [];

        if (empty($providers)) {
            return ['level' => 'BLOCKER', 'status' => 'WARN', 'message' => 'No providers declared in manifest'];
        }

        if (empty($this->bootstrapProvidersContent)) {
            return ['level' => 'BLOCKER', 'status' => 'WARN', 'message' => 'bootstrap/providers.php not found — cannot verify'];
        }

        $unregistered = [];

        foreach ($providers as $provider) {
            // Match the FQCN as a PHP class string literal or class constant reference.
            // preg_quote escapes backslashes so they match literal backslashes in source.
            $pattern = '/'.preg_quote($provider, '/').'/';
            if (! preg_match($pattern, $this->bootstrapProvidersContent)) {
                $unregistered[] = $provider;
            }
        }

        if (empty($unregistered)) {
            return ['level' => 'BLOCKER', 'status' => 'PASS', 'message' => 'OK'];
        }

        return [
            'level'   => 'BLOCKER',
            'status'  => 'FAIL',
            'message' => 'Providers not in bootstrap/providers.php: '.implode(', ', $unregistered),
        ];
    }

    // ── Reporting ─────────────────────────────────────────────────────────────

    private function printReport(): void
    {
        $totalBlockers = 0;
        $affectedModules = 0;

        foreach ($this->results as $name => $result) {
            $hasBlocker = $result['has_blocker'];

            if ($hasBlocker) {
                $affectedModules++;
            }

            $moduleLabel = $hasBlocker
                ? "<error>FAIL</error>  {$name}"
                : "<info>PASS</info>  {$name}";

            $this->line("── {$moduleLabel}");

            foreach ($result['checks'] as $checkName => $check) {
                $status = $check['status'];
                $tag = match ($status) {
                    'PASS' => '<info>PASS</info>',
                    'FAIL' => '<error>FAIL</error>',
                    default => '<comment>WARN</comment>',
                };

                $this->line("   {$tag}  [{$check['level']}] {$checkName}: {$check['message']}");

                if ($status === 'FAIL') {
                    $totalBlockers++;
                }
            }

            $this->line('');
        }

        if ($totalBlockers === 0) {
            $this->info('✓  All production checks passed. Ready to deploy.');
        } else {
            $this->error("✗  {$totalBlockers} BLOCKER(s) across {$affectedModules} module(s). Deployment blocked.");
        }
    }

    /**
     * Build the JSON-serialisable report structure.
     *
     * @return array<string, mixed>
     */
    private function buildJsonReport(): array
    {
        $hasBlocker = collect($this->results)->contains(fn (array $r) => $r['has_blocker'] === true);

        return [
            'status'  => $hasBlocker ? 'FAIL' : 'PASS',
            'modules' => $this->results,
        ];
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function loadBootstrapProviders(): string
    {
        $path = base_path('bootstrap/providers.php');

        return file_exists($path) ? file_get_contents($path) : '';
    }
}
