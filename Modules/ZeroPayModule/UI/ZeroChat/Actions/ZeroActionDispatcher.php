<?php

namespace Modules\ZeroPayModule\UI\ZeroChat\Actions;

class ZeroActionDispatcher
{
    public function dispatch(string $action, array $payload = []): array
    {
        return ['status' => 'queued', 'action' => $action, 'payload' => $payload];
    }
}
