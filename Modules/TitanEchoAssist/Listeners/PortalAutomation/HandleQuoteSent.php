<?php

namespace Modules\TitanEchoAssist\Listeners\PortalAutomation;

use App\Events\EstimateSent;
use Modules\TitanEchoAssist\Services\ChatbotPortalAutomationService;

class HandleQuoteSent
{
    public function __construct(private readonly ChatbotPortalAutomationService $automationService) {}

    public function handle(EstimateSent $event): void
    {
        $estimate = $event->estimate;
        $estimate->loadMissing('customer');

        $this->automationService->trigger('quote_sent', [
            ...$this->automationService->payloadFromCustomer($estimate->organization_id, $estimate->customer_id),
            'estimate_id' => $estimate->id,
            'sent_at' => $estimate->sent_at?->toDateTimeString(),
        ]);
    }
}

