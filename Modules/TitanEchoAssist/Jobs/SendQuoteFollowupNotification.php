<?php

namespace Modules\TitanEchoAssist\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\TitanEchoAssist\Services\ChatbotPortalAutomationService;

class SendQuoteFollowupNotification implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(private readonly array $payload) {}

    public function handle(ChatbotPortalAutomationService $automationService): void
    {
        $automationService->processQuoteFollowup($this->payload);
    }
}

