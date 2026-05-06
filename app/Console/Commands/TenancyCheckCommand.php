<?php

namespace App\Console\Commands;

use App\Contracts\TenantAware;
use App\Models\Scopes\TenantScope;
use App\Tenancy\CurrentTenant;
use App\Tenancy\TenantResolver;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

/**
 * tenancy:check
 *
 * Diagnostic command that verifies the tenancy enforcement layer is correctly
 * wired in the running application.  Run this command to confirm:
 *
 *  1. TenantResolver and CurrentTenant are bound in the container.
 *  2. Every app Model that carries an `organization_id` column implements
 *     the TenantAware interface.
 *  3. TenantAware models have TenantScope registered as a global scope.
 *  4. The titan_module_vectors table (AI memory) has a company_id column so
 *     cross-tenant vector leakage is preventable.
 *
 * Exit codes:
 *   0 — all checks passed
 *   1 — one or more issues found
 */
class TenancyCheckCommand extends Command
{
    protected $signature = 'tenancy:check
                            {--json : Output results as JSON}';

    protected $description = 'Verify application-wide tenant isolation is correctly enforced.';

    /** @var array<string, array{ok: bool, message: string}> */
    private array $checks = [];

    public function handle(): int
    {
        $this->checkContainerBindings();
        $this->checkModelScopes();
        $this->checkAiVectorTenant();

        if ($this->option('json')) {
            $this->line(json_encode($this->checks, JSON_PRETTY_PRINT));
        } else {
            $this->printTable();
        }

        $anyFailed = collect($this->checks)->contains(fn ($c) => ! $c['ok']);

        return $anyFailed ? self::FAILURE : self::SUCCESS;
    }

    // ── Container bindings ────────────────────────────────────────────────────

    private function checkContainerBindings(): void
    {
        foreach ([TenantResolver::class, CurrentTenant::class] as $abstract) {
            $short = class_basename($abstract);

            try {
                $instance = app($abstract);
                $this->checks["container.{$short}"] = [
                    'ok' => true,
                    'message' => 'Bound as '.get_class($instance),
                ];
            } catch (\Throwable $e) {
                $this->checks["container.{$short}"] = [
                    'ok' => false,
                    'message' => 'Not bound: '.$e->getMessage(),
                ];
            }
        }
    }

    // ── Model scopes ──────────────────────────────────────────────────────────

    private function checkModelScopes(): void
    {
        $modelsPath = app_path('Models');

        if (! is_dir($modelsPath)) {
            $this->checks['models.directory'] = [
                'ok' => false,
                'message' => 'app/Models directory not found',
            ];

            return;
        }

        foreach (File::allFiles($modelsPath) as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $class = 'App\\Models\\'.str_replace(
                ['/', '.php'],
                ['\\', ''],
                $file->getRelativePathname()
            );

            if (! class_exists($class)) {
                continue;
            }

            $reflection = new \ReflectionClass($class);

            if ($reflection->isAbstract() || ! $reflection->isInstantiable()) {
                continue;
            }

            if (! is_subclass_of($class, Model::class)) {
                continue;
            }

            // Check whether the underlying table has an organization_id column.
            try {
                /** @var Model $instance */
                $instance = new $class;
                $table = $instance->getTable();
                $hasCol = Schema::hasColumn($table, 'organization_id');
            } catch (\Throwable) {
                continue;
            }

            if (! $hasCol) {
                // Not a tenant-partitioned table — skip silently.
                continue;
            }

            $key = 'model.'.class_basename($class);

            $implementsInterface = is_a($class, TenantAware::class, true);
            $hasScope = $implementsInterface
                ? array_key_exists(TenantScope::class, (new $class)->getGlobalScopes())
                : false;

            if ($implementsInterface && $hasScope) {
                $this->checks[$key] = ['ok' => true, 'message' => 'TenantAware + TenantScope registered'];
            } elseif ($implementsInterface) {
                $this->checks[$key] = ['ok' => false, 'message' => 'Implements TenantAware but TenantScope is missing'];
            } else {
                $this->checks[$key] = ['ok' => false, 'message' => 'Has organization_id but does not implement TenantAware'];
            }
        }
    }

    // ── AI vector store tenant column ─────────────────────────────────────────

    private function checkAiVectorTenant(): void
    {
        $key = 'ai_vectors.company_id_column';

        try {
            if (Schema::hasTable('titan_module_vectors')) {
                $hasCol = Schema::hasColumn('titan_module_vectors', 'company_id');
                $this->checks[$key] = $hasCol
                    ? ['ok' => true,  'message' => 'company_id column present on titan_module_vectors']
                    : ['ok' => false, 'message' => 'titan_module_vectors is missing the company_id column'];
            } else {
                $this->checks[$key] = ['ok' => true, 'message' => 'titan_module_vectors table not found (vector store not in use)'];
            }
        } catch (\Throwable $e) {
            $this->checks[$key] = ['ok' => false, 'message' => 'Could not check table: '.$e->getMessage()];
        }
    }

    // ── Output ────────────────────────────────────────────────────────────────

    private function printTable(): void
    {
        $failed = 0;

        foreach ($this->checks as $name => $check) {
            $icon = $check['ok'] ? '<info>✓</info>' : '<error>✗</error>';
            $status = $check['ok'] ? '<info>PASS</info>' : '<error>FAIL</error>';

            $this->line("  {$icon} [{$status}] {$name}: {$check['message']}");

            if (! $check['ok']) {
                $failed++;
            }
        }

        $this->line('');

        if ($failed === 0) {
            $this->info('All tenancy checks passed.');
        } else {
            $this->error("{$failed} tenancy check(s) failed.");
        }
    }
}
