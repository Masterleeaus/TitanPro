<?php

namespace Modules\TitanEchoAssist\Listeners\PortalAutomation;

use App\Events\JobStatusChanged;
use App\Models\Job;
use Modules\TitanEchoAssist\Services\ChatbotPortalAutomationService;

class HandleJobCompleted
{
    public function __construct(private readonly ChatbotPortalAutomationService $automationService) {}

    public function handle(JobStatusChanged $event): void
    {
        if ($event->newStatus !== Job::STATUS_COMPLETED) {
            return;
        }

        $job = $event->job;
        $job->loadMissing('customer');

        $this->automationService->trigger('job_completed', [
            ...$this->automationService->payloadFromCustomer($job->organization_id, $job->customer_id),
            'job_id' => $job->id,
            'completed_at' => $job->completed_at?->toDateTimeString(),
        ]);
    }
}

