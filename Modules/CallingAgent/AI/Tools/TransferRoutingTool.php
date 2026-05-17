<?php

namespace Modules\CallingAgent\AI\Tools;

use Modules\CallingAgent\Services\TransferRoutingService;

final class TransferRoutingTool
{
    private TransferRoutingService $routingService;

    public function __construct(?TransferRoutingService $routingService = null)
    {
        $this->routingService = $routingService ?? app(TransferRoutingService::class);
    }

    public function execute(array $input): array
    {
        return [
            'decision' => $this->routingService->route(
                (array) ($input['outcome'] ?? []),
                (array) ($input['routing'] ?? []),
                (array) ($input['caller'] ?? []),
            ),
        ];
    }
}
