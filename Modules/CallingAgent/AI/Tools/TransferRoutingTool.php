<?php

namespace Modules\CallingAgent\AI\Tools;

use Modules\CallingAgent\Services\TransferRoutingService;

final class TransferRoutingTool
{
    public function __construct(private readonly TransferRoutingService $routingService = new TransferRoutingService()) {}

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
