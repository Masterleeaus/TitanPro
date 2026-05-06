<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Verifies the runtime health of every enabled module.
 *
 * For each module discovered in the Modules/ directory the command checks:
 *  1. module.json is present and parseable
 *  2. At least one provider class is loadable (class_exists)
 *  3. Required environment variables declared in module.json are set
 *  4. Route files declared in module.json (or conventional paths) are present
 *
 * Exit code:
 *   0 — all modules healthy
 *   1 — one or more modules are unhealthy
 */
class ModulesHealthCommand extends Command
{
    protected $signature = 'modules:health
                            {--json : Output results as JSON}
                            {--module= : Check a single named module}';

    protected $description = 'Check the runtime health of every enabled module.';

    /** @var array<string, array<string, mixed>> */
    private array $results = [];

    public function handle(): int
    {
        $modulesBase = base_path('Modules');

        if (! is_dir($modulesBase)) {
            $this->error('Modules directory not found: '.$modulesBase);

            return self::FAILURE;
        }

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
            $this->line(json_encode($this->results, JSON_PRETTY_PRINT));
        } else {
            $this->printTable();
        }

        $anyFailed = collect($this->results)->contains(
            fn (array $r) => $r['healthy'] === false
        );

        return $anyFailed ? self::FAILURE : self::SUCCESS;
    }

    /**
     * Run all checks for a single module directory and return the result array.
     *
     * @return array{healthy: bool, checks: array<string, array{ok: bool, message: string}>}
     */
    private function checkModule(string $dir, string $name): array
    {
        $checks = [];

        // ── 1. manifest present ──────────────────────────────────────────────
        $manifestPath = $dir.'/module.json';
        $manifest = null;

        if (! file_exists($manifestPath)) {
            $checks['manifest_present'] = [
                'ok' => false,
                'message' => 'module.json not found',
            ];
        } else {
            $decoded = json_decode(file_get_contents($manifestPath), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $checks['manifest_present'] = [
                    'ok' => false,
                    'message' => 'module.json is not valid JSON: '.json_last_error_msg(),
                ];
            } else {
                $manifest = $decoded;
                $checks['manifest_present'] = ['ok' => true, 'message' => 'OK'];
            }
        }

        // ── 2. provider loadable ─────────────────────────────────────────────
        $providers = $manifest['providers'] ?? [];

        if (empty($providers)) {
            $checks['provider_loadable'] = [
                'ok' => false,
                'message' => 'No providers declared in module.json',
            ];
        } else {
            $allLoaded = true;
            $missing = [];

            foreach ($providers as $provider) {
                if (! class_exists($provider)) {
                    $allLoaded = false;
                    $missing[] = $provider;
                }
            }

            $checks['provider_loadable'] = $allLoaded
                ? ['ok' => true, 'message' => 'OK']
                : ['ok' => false, 'message' => 'Missing: '.implode(', ', $missing)];
        }

        // ── 3. required env vars present ─────────────────────────────────────
        $requiredEnv = $manifest['required_env'] ?? [];

        if (empty($requiredEnv)) {
            $checks['required_env'] = ['ok' => true, 'message' => 'none declared'];
        } else {
            $missingEnv = array_filter($requiredEnv, fn ($v) => env($v) === null);

            $checks['required_env'] = empty($missingEnv)
                ? ['ok' => true, 'message' => 'OK']
                : ['ok' => false, 'message' => 'Missing env vars: '.implode(', ', $missingEnv)];
        }

        // ── 4. declared route files present ──────────────────────────────────
        // Check both manifest-declared route files and conventional paths.
        $declaredRoutes = $manifest['routes'] ?? [];
        $conventionalRoutes = array_filter([
            $dir.'/Routes/web.php',
            $dir.'/Routes/api.php',
        ], 'file_exists');

        if (empty($declaredRoutes) && empty($conventionalRoutes)) {
            $checks['route_files'] = ['ok' => true, 'message' => 'none declared'];
        } else {
            $missingRoutes = array_filter($declaredRoutes, function (string $path) use ($dir): bool {
                // Allow absolute or module-relative paths
                $abs = str_starts_with($path, '/') ? $path : $dir.'/'.$path;

                return ! file_exists($abs);
            });

            $checks['route_files'] = empty($missingRoutes)
                ? ['ok' => true, 'message' => 'OK']
                : ['ok' => false, 'message' => 'Missing route files: '.implode(', ', $missingRoutes)];
        }

        $healthy = collect($checks)->every(fn ($c) => $c['ok'] === true);

        return ['healthy' => $healthy, 'checks' => $checks];
    }

    private function printTable(): void
    {
        foreach ($this->results as $name => $result) {
            $status = $result['healthy']
                ? '<info>HEALTHY</info>'
                : '<error>UNHEALTHY</error>';

            $this->line("── {$name}: {$status}");

            foreach ($result['checks'] as $check => $detail) {
                $icon = $detail['ok'] ? '<info>✓</info>' : '<error>✗</error>';
                $this->line("   {$icon} {$check}: {$detail['message']}");
            }

            $this->line('');
        }
    }
}
