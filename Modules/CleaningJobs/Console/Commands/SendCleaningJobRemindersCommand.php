<?php

namespace Modules\CleaningJobs\Console\Commands;

use Illuminate\Console\Command;
use Modules\CleaningJobs\Automation\Pipelines\JobAutomationPipeline;
use Modules\CleaningJobs\Models\WorkOrder;

class SendCleaningJobRemindersCommand extends Command
{
    protected $signature = 'cleaningjobs:send-reminders';
    protected $description = 'Dispatch upcoming cleaning job reminders.';

    public function handle(JobAutomationPipeline $pipeline): int
    {
        $count = 0;
        WorkOrder::query()
            ->whereNotNull('scheduled_for')
            ->whereIn('status', ['open', 'scheduled'])
            ->whereBetween('scheduled_for', [now(), now()->addDay()])
            ->chunkById(100, function ($orders) use ($pipeline, &$count) {
                foreach ($orders as $order) {
                    $count += $pipeline->run($order) ? 1 : 0;
                }
            });
        $this->info("Queued {$count} reminder(s).");
        return self::SUCCESS;
    }
}
