<?php

namespace Modules\TitanNexus\Automation\Pipelines;

use Modules\TitanNexus\Services\ChannelOrchestratorService;

class MultiChannelOutreachPipeline
{
    public function __construct(private ChannelOrchestratorService $channels) {}

    public function __invoke(array $lead, string $message, array $campaign = []): array
    {
        return $this->channels->route($lead, $message, $campaign);
    }
}
