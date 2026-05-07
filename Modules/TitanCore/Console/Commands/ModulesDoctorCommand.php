<?php

namespace Modules\TitanCore\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Modules\TitanCore\Support\ManifestSchemaValidator;
use Modules\TitanCore\Support\ModuleDependencyGraph;

/**
 * Diagnoses the module dependency graph and surfaces all issues:
 *  - Missing / disabled required modules
 *  - Version constraint violations
 *  - Conflicting modules
 *  - Circular dependency cycles
 *  - Suggested safe load order
 *  - Manifest schema validation errors/warnings
 */
class ModulesDoctorCommand extends Command
{
    protected $signature = 'modules:doctor
                            {--skip-schema : Skip manifest schema validation}';

    protected $description = 'Diagnose the module dependency graph (missing deps, conflicts, cycles, load order, manifest schemas).';

    public function handle(ModuleDependencyGraph $graph): int
    {
        $graph->build();

        $this->components->info('Module Dependency Doctor');
        $this->newLine();

        $hasProblems = false;

        // ── 1. Cycle detection ────────────────────────────────────────────────
        $cycles = $graph->detectCycles();

        if (! empty($cycles)) {
            $hasProblems = true;
            $this->components->error('Circular dependencies detected:');

            foreach ($cycles as $cycle) {
                $this->line('  <fg=red>↻</> '.implode(' → ', $cycle));
            }

            $this->newLine();
        } else {
            $this->components->twoColumnDetail('<fg=green>✓ No circular dependencies</>', '');
        }

        // ── 2. Per-module dependency issues ───────────────────────────────────
        $issues = $graph->getAllIssues();

        if (! empty($issues)) {
            $hasProblems = true;
            $this->components->error('Dependency issues found:');

            foreach ($issues as $module => $result) {
                if (! empty($result['errors'])) {
                    foreach ($result['errors'] as $err) {
                        $this->line("  <fg=red>✗</> <fg=cyan>{$module}</>: {$err}");
                    }
                }

                if (! empty($result['warnings'])) {
                    foreach ($result['warnings'] as $warn) {
                        $this->line("  <fg=yellow>⚠</> <fg=cyan>{$module}</>: {$warn}");
                    }
                }
            }

            $this->newLine();
        } else {
            $this->components->twoColumnDetail('<fg=green>✓ All dependency constraints satisfied</>', '');
        }

        // ── 3. Safe-boot provider failures ────────────────────────────────────
        $bootFailures = app()->bound('titan.module_boot_failures')
            ? app('titan.module_boot_failures')
            : [];

        if (is_array($bootFailures) && ! empty($bootFailures)) {
            $hasProblems = true;
            $this->components->warn('Safe-boot provider failures detected:');

            foreach ($bootFailures as $failure) {
                $module = $failure['module'] ?? 'unknown-module';
                $provider = $failure['provider'] ?? 'unknown-provider';
                $error = $failure['error'] ?? 'unknown error';

                $this->line("  <fg=yellow>⚠</> <fg=cyan>{$module}</>: {$provider} — {$error}");
            }

            $this->newLine();
        } else {
            $this->components->twoColumnDetail('<fg=green>✓ No safe-boot provider failures</>', '');
        }

        // ── 4. Manifest schema validation ─────────────────────────────────────
        if (! $this->option('skip-schema')) {
            $schemaProblems = $this->runSchemaValidation();
            if ($schemaProblems) {
                $hasProblems = true;
            }
        }

        // ── 5. Load order ─────────────────────────────────────────────────────
        $this->newLine();
        $this->components->info('Resolved load order:');
        $order = $graph->resolveLoadOrder();

        foreach ($order as $i => $name) {
            $num = str_pad((string) ($i + 1), 3, ' ', STR_PAD_LEFT);
            $nodes = $graph->getNodes();
            $enabled = isset($nodes[$name]) && $nodes[$name]['enabled'];
            $status = $enabled ? '<fg=green>enabled</>' : '<fg=red>disabled</>';

            $this->line("  {$num}. <fg=cyan>{$name}</> [{$status}]");
        }

        $this->newLine();

        if ($hasProblems) {
            $this->components->warn('Doctor found problems. Fix the issues listed above.');

            return self::FAILURE;
        }

        $this->components->info('All checks passed. Module dependency graph is healthy.');

        return self::SUCCESS;
    }

    /**
     * Run manifest schema validation across all modules.
     *
     * Returns true if any failures were found.
     */
    private function runSchemaValidation(): bool
    {
        $this->newLine();
        $this->components->info('Manifest Schema Validation:');

        $modulesBase = base_path(config('titan-modules.path', 'Modules'));

        if (! is_dir($modulesBase)) {
            $this->components->warn('Modules directory not found. Skipping schema validation.');

            return false;
        }

        $validator     = new ManifestSchemaValidator();
        $strict        = (bool) config('titan-modules.strict_manifest_validation', false);
        $hasFailures   = false;
        $hasWarnings   = false;
        $totalChecked  = 0;

        foreach (File::directories($modulesBase) as $moduleDir) {
            $moduleName = basename($moduleDir);
            $results    = $validator->validateModule($moduleDir);

            foreach ($results as $result) {
                $totalChecked++;

                if ($result->isValid() && ! $result->hasWarnings()) {
                    continue;
                }

                if (! $result->isValid()) {
                    $hasFailures = true;
                    foreach ($result->errors() as $err) {
                        $this->line("  <fg=red>✗</> <fg=cyan>{$moduleName}/{$result->label()}</>: {$err}");
                    }
                } else {
                    $hasWarnings = true;
                    foreach ($result->warnings() as $warn) {
                        $this->line("  <fg=yellow>⚠</> <fg=cyan>{$moduleName}/{$result->label()}</>: {$warn}");
                    }
                }
            }
        }

        if (! $hasFailures && ! $hasWarnings) {
            $this->components->twoColumnDetail(
                sprintf('<fg=green>✓ All %d manifest(s) valid</>', $totalChecked),
                ''
            );
        }

        if ($hasFailures && $strict) {
            $this->newLine();
            $this->components->error('Strict manifest validation is enabled. Fix all schema errors before continuing.');
        }

        return $hasFailures;
    }
}
