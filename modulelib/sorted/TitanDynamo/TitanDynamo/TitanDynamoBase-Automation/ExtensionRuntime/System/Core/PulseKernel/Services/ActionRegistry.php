<?php

namespace App\Extensions\TitanPulse\System\Core\PulseKernel\Services;

use App\Extensions\TitanPulse\System\Core\PulseKernel\Services\Handlers\WebhookHandler;
use App\Extensions\TitanPulse\System\Core\PulseKernel\Services\Handlers\NotifyHandler;
use App\Extensions\TitanPulse\System\Core\PulseKernel\Services\Handlers\CreateTaskHandler;

class ActionRegistry
{
    public static function all(): array
    {
        return [
            ['key' => 'notify', 'label' => 'Notify (stub)', 'handler' => NotifyHandler::class],
            ['key' => 'webhook', 'label' => 'Webhook POST', 'handler' => WebhookHandler::class],

            // System integrations (governed)
            ['key' => 'core.task.create', 'label' => 'Create core Task', 'handler' => CreateTaskHandler::class],
        ];
    }

    public static function handlerFor(string $key): ?string
    {
        foreach (self::all() as $a) {
            if ($a['key'] === $key) return $a['handler'];
        }
        return null;
    }
}
