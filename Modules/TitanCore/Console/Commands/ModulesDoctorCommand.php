<?php

namespace Modules\TitanCore\Console\Commands;

use Illuminate\Console\Command;
use Modules\TitanCore\Support\ModuleDependencyGraph;

/**
 * Diagnoses the module dependency graph and surfaces all issues:
 *  - Missing / disabled required modules
 *  - Version constraint violations
 *  - Conflicting modules
 *  - Circular dependency cycles
 *  - Suggested safe load order
 */
class ModulesDoctorCommand extends Command
{
    protected $signature = 'modules:doctor';

    protected $description = 'Diagnose the module dependency graph (missing deps, conflicts, cycles, load order).';

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

        // ── 3. Load order ─────────────────────────────────────────────────────
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
}
