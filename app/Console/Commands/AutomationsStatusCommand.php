<?php

namespace App\Console\Commands;

use App\Models\AutomationRun;
use Illuminate\Console\Command;

/**
 * automations:status
 *
 * Displays a summary table of recent automation runs grouped by status.
 *
 * Usage:
 *   php artisan automations:status
 *   php artisan automations:status --automation=crm.deal_won
 *   php artisan automations:status --status=failed
 *   php artisan automations:status --limit=50
 */
class AutomationsStatusCommand extends Command
{
    protected $signature = 'automations:status
                            {--automation= : Filter by automation id}
                            {--status=     : Filter by status (queued|running|completed|failed)}
                            {--limit=20    : Number of rows to show}';

    protected $description = 'Show recent automation run statuses';

    public function handle(): int
    {
        $query = AutomationRun::query()->latest();

        if ($id = $this->option('automation')) {
            $query->where('automation_id', $id);
        }

        if ($status = $this->option('status')) {
            $query->where('status', $status);
        }

        $limit = (int) ($this->option('limit') ?? 20);
        $runs  = $query->limit($limit)->get();

        if ($runs->isEmpty()) {
            $this->info('No automation runs found.');
            return self::SUCCESS;
        }

        // Summary counts
        $counts = AutomationRun::query()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $this->info('Automation run summary:');
        foreach ($counts as $status => $total) {
            $this->line("  {$status}: {$total}");
        }

        $this->newLine();

        $this->table(
            ['ID', 'Automation', 'Trigger', 'Status', 'Attempts', 'Started At', 'Completed At'],
            $runs->map(fn (AutomationRun $r) => [
                $r->id,
                $r->automation_id,
                $r->trigger,
                $r->status,
                "{$r->attempts}/{$r->max_attempts}",
                $r->started_at?->format('Y-m-d H:i:s') ?? '—',
                $r->completed_at?->format('Y-m-d H:i:s') ?? '—',
            ]),
        );

        return self::SUCCESS;
    }
}
