<?php

namespace App\Console\Commands;

use App\Jobs\ExecuteAutomationJob;
use App\Models\AutomationRun;
use App\Platform\Automation\AutomationRegistry;
use Illuminate\Console\Command;

/**
 * automations:requeue
 *
 * Re-queues failed (or stuck) automation runs so they are retried.
 * Resets the attempt counter and status to "queued" before dispatching.
 *
 * Usage:
 *   php artisan automations:requeue
 *   php artisan automations:requeue --automation=crm.deal_won
 *   php artisan automations:requeue --id=42
 *   php artisan automations:requeue --reset-attempts
 */
class AutomationsRequeueCommand extends Command
{
    protected $signature = 'automations:requeue
                            {--automation= : Requeue all failed runs for this automation id}
                            {--id=         : Requeue a single run by its database id}
                            {--reset-attempts : Reset the attempts counter before re-dispatching}';

    protected $description = 'Requeue failed automation runs for retry';

    public function __construct(private readonly AutomationRegistry $registry)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $query = AutomationRun::where('status', AutomationRun::STATUS_FAILED);

        if ($id = $this->option('id')) {
            $query->where('id', (int) $id);
        } elseif ($automationId = $this->option('automation')) {
            $query->where('automation_id', $automationId);
        }

        $runs = $query->get();

        if ($runs->isEmpty()) {
            $this->info('No matching failed runs found.');
            return self::SUCCESS;
        }

        $requeued = 0;

        foreach ($runs as $run) {
            $automation = $this->registry->find($run->automation_id);

            if (! $automation) {
                $this->warn("Automation [{$run->automation_id}] is no longer registered — skipping run #{$run->id}.");
                continue;
            }

            $updates = [
                'status'    => AutomationRun::STATUS_QUEUED,
                'exception' => null,
            ];

            if ($this->option('reset-attempts')) {
                $updates['attempts'] = 0;
            }

            $run->update($updates);

            ExecuteAutomationJob::dispatch($run->id, $automation);

            $requeued++;
            $this->line("  Requeued run #{$run->id} ({$run->automation_id})");
        }

        $this->info("Requeued {$requeued} automation run(s).");

        return self::SUCCESS;
    }
}
