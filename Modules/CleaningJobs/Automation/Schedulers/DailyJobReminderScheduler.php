<?php

namespace Modules\CleaningJobs\Automation\Schedulers;

use Modules\CleaningJobs\Automation\Pipelines\JobAutomationPipeline;
use Modules\CleaningJobs\Models\WorkOrder;
use Modules\CleaningJobs\Workflows\Definitions\CleaningJobWorkflow;

class DailyJobReminderScheduler
{
    public function __construct(
        private readonly JobAutomationPipeline $pipeline,
    ) {}

    public function handle(): int
    {
        $count = 0;

        WorkOrder::query()
            ->whereDate('scheduled_for', today())
            ->whereIn('status', [
                CleaningJobWorkflow::STATUS_OPEN,
                CleaningJobWorkflow::STATUS_SCHEDULED,
            ])
            ->cursor()
            ->each(function (WorkOrder $order) use (&$count): void {
                if ($this->pipeline->run($order)) {
                    $count++;
                }
            });

        return $count;
    }
}
