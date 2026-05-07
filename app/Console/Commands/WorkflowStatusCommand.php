<?php

namespace App\Console\Commands;

use App\Models\WorkflowInstance;
use Illuminate\Console\Command;

/**
 * Display a summary of all workflow instances, optionally filtered by workflow
 * ID or status.
 *
 * Usage:
 *   php artisan workflows:status
 *   php artisan workflows:status --workflow=onboarding_flow
 *   php artisan workflows:status --status=failed
 *   php artisan workflows:status --json
 */
class WorkflowStatusCommand extends Command
{
    protected $signature = 'workflows:status
                            {--workflow= : Filter by workflow ID (slug)}
                            {--status=  : Filter by status (pending|running|waiting|completed|failed|cancelled)}
                            {--limit=50 : Maximum number of records to display}
                            {--json     : Output as JSON}';

    protected $description = 'Show the status of workflow instances.';

    public function handle(): int
    {
        $query = WorkflowInstance::query()
            ->orderByDesc('updated_at')
            ->limit((int) $this->option('limit'));

        if ($workflow = $this->option('workflow')) {
            $query->where('workflow_id', $workflow);
        }

        if ($status = $this->option('status')) {
            $query->where('status', $status);
        }

        $instances = $query->get();

        if ($instances->isEmpty()) {
            $this->info('No workflow instances found.');

            return self::SUCCESS;
        }

        if ($this->option('json')) {
            $this->line($instances->toJson(JSON_PRETTY_PRINT));

            return self::SUCCESS;
        }

        $this->printSummary($instances);

        return self::SUCCESS;
    }

    /** @param \Illuminate\Database\Eloquent\Collection<int, WorkflowInstance> $instances */
    private function printSummary(\Illuminate\Database\Eloquent\Collection $instances): void
    {
        // Group by status for a quick headline
        $byStatus = $instances->groupBy('status');

        $this->info(sprintf(
            'Found %d instance(s): %s',
            $instances->count(),
            $byStatus->map(fn ($g, $s) => "{$s}={$g->count()}")->implode(', '),
        ));

        $this->newLine();

        $rows = $instances->map(fn (WorkflowInstance $i) => [
            $i->id,
            $i->workflow_id,
            $i->workflow_version,
            $i->status,
            $i->current_step ?? '—',
            $i->initiated_by ?? 'system',
            $i->started_at?->toDateTimeString() ?? '—',
            $i->updated_at?->toDateTimeString() ?? '—',
        ])->all();

        $this->table(
            ['ID', 'Workflow', 'Version', 'Status', 'Current Step', 'Actor', 'Started', 'Updated'],
            $rows,
        );
    }
}
