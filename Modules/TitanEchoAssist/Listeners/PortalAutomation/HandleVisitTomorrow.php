<?php

namespace Modules\TitanEchoAssist\Listeners\PortalAutomation;

use App\Models\Job;
use Modules\TitanEchoAssist\Services\ChatbotPortalAutomationService;

class HandleVisitTomorrow
{
    public function __construct(private readonly ChatbotPortalAutomationService $automationService) {}

    public function handle(Job $job): void
    {
        $job->loadMissing('customer');

        $this->automationService->trigger('visit_tomorrow', [
            ...$this->automationService->payloadFromCustomer($job->organization_id, $job->customer_id),
            'job_id' => $job->id,
            'scheduled_date' => $job->scheduled_at?->toDateString(),
            'scheduled_time' => $job->scheduled_at?->format('g:i A'),
        ]);
    }
}

