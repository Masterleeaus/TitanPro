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

        // ── 3. Manifest schema validation ─────────────────────────────────────
        if (! $this->option('skip-schema')) {
            $schemaProblems = $this->runSchemaValidation();
            if ($schemaProblems) {
                $hasProblems = true;
            }
        }

        // ── 4. Automation handler class checks ───────────────────────────────
        if ($this->runAutomationHandlerValidation()) {
            $hasProblems = true;
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

    /**
     * Validate that handler classes declared in automation manifests exist.
     *
     * Returns true if any missing handlers were found.
     */
    private function runAutomationHandlerValidation(): bool
    {
        $this->newLine();
        $this->components->info('Automation Manifest Handler Validation:');

        $modulesBase = base_path(config('titan-modules.path', 'Modules'));
        if (! is_dir($modulesBase)) {
            $this->components->warn('Modules directory not found. Skipping automation handler validation.');

            return false;
        }

        $statusMap = $this->moduleStatusMap($modulesBase);
        $hasFailures = false;

        foreach (File::directories($modulesBase) as $moduleDir) {
            $moduleName = basename($moduleDir);

            if (! $this->isModuleEnabled($moduleDir, $moduleName, $statusMap)) {
                continue;
            }

            $manifestPath = $moduleDir.'/manifests/automation.manifest.json';
            if (! is_file($manifestPath)) {
                continue;
            }

            $manifest = $this->decodeJsonFile($manifestPath);
            if (! is_array($manifest) || ($manifest['enabled'] ?? true) === false) {
                continue;
            }

            foreach ((array) ($manifest['handlers'] ?? []) as $handler) {
                $class = $this->resolveManifestClass($moduleName, $handler, 'Handlers');

                if ($class === null || class_exists($class)) {
                    continue;
                }

                $hasFailures = true;
                $this->line("  <fg=red>✗</> <fg=cyan>{$moduleName}/automation.manifest</>: missing handler class {$class}");
            }
        }

        if (! $hasFailures) {
            $this->components->twoColumnDetail('<fg=green>✓ All declared automation handlers resolve</>', '');
        }

        return $hasFailures;
    }

    /**
     * @param  array<string, bool>  $statusMap
     */
    private function isModuleEnabled(string $moduleDir, string $moduleName, array $statusMap): bool
    {
        if (array_key_exists($moduleName, $statusMap) && $statusMap[$moduleName] === false) {
            return false;
        }

        $moduleJsonPath = $moduleDir.'/module.json';
        if (! is_file($moduleJsonPath)) {
            return true;
        }

        $moduleJson = $this->decodeJsonFile($moduleJsonPath);
        if (! is_array($moduleJson)) {
            return true;
        }

        if (array_key_exists('active', $moduleJson) && (int) $moduleJson['active'] === 0) {
            return false;
        }

        return ! (array_key_exists('enabled', $moduleJson) && $moduleJson['enabled'] === false);
    }

    /**
     * @return array<string, bool>
     */
    private function moduleStatusMap(string $modulesBase): array
    {
        $statusFiles = [
            dirname($modulesBase).'/module_statuses.json',
            dirname($modulesBase).'/modules_statuses.json',
        ];

        foreach ($statusFiles as $statusFile) {
            if (! is_file($statusFile)) {
                continue;
            }

            $decoded = $this->decodeJsonFile($statusFile);
            if (! is_array($decoded)) {
                return [];
            }

            return array_map(fn (mixed $value): bool => (bool) $value, $decoded);
        }

        return [];
    }

    /**
     * @param  mixed  $entry
     */
    private function resolveManifestClass(string $moduleName, mixed $entry, string $defaultSubNamespace): ?string
    {
        if (is_string($entry) && $entry !== '') {
            return str_contains($entry, '\\')
                ? $entry
                : "Modules\\{$moduleName}\\Automation\\{$defaultSubNamespace}\\{$entry}";
        }

        if (! is_array($entry)) {
            return null;
        }

        $candidate = $entry['class'] ?? $entry['key'] ?? $entry['id'] ?? $entry['name'] ?? null;
        if (! is_string($candidate) || $candidate === '') {
            return null;
        }

        return str_contains($candidate, '\\')
            ? $candidate
            : "Modules\\{$moduleName}\\Automation\\{$defaultSubNamespace}\\{$candidate}";
    }

    /**
     * @return array<string, mixed>|null
     */
    private function decodeJsonFile(string $path): ?array
    {
        if (! is_file($path)) {
            return null;
        }

        $raw = file_get_contents($path);
        if ($raw === false) {
            return null;
        }

        $decoded = json_decode($raw, true);

        return is_array($decoded) ? $decoded : null;
    }
}
