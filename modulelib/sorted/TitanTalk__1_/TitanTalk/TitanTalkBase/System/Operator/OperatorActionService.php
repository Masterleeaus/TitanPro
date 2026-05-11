<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Operator;

use App\Extensions\MarketingBot\System\Models\MarketingConversation;
use App\Extensions\MarketingBot\System\Services\TitanTalk\Tools\PredixToolBridge;
use App\Extensions\MarketingBot\System\Signals\TitanTalkSignalBridge;
use InvalidArgumentException;

class OperatorActionService
{
    public function __construct(
        protected PredixToolBridge $predixToolBridge,
        protected TitanTalkSignalBridge $signalBridge,
    ) {}

    /**
     * @param array<string,mixed> $payload
     * @return array<string,mixed>
     */
    public function execute(MarketingConversation $conversation, string $action, array $payload = []): array
    {
        $allowed = [
            'ticket.create',
            'booking.create',
            'invoice.send_copy',
            'human.handoff',
        ];

        if (! in_array($action, $allowed, true)) {
            throw new InvalidArgumentException('Unsupported operator action: ' . $action);
        }

        $plan = $this->predixToolBridge->plan($conversation, [
            'name' => $action,
            'mode' => str_contains($action, 'create') || str_contains($action, 'send') ? 'write' : 'read',
            'approval_required' => in_array($action, ['booking.create', 'invoice.send_copy'], true),
        ], $payload);

        $signal = $this->signalBridge->emitFromCommand($conversation, [
            'command' => $action,
            'params' => $payload,
            'requires_confirmation' => (bool) ($plan['approval_required'] ?? false),
            'is_actionable' => true,
        ], (string) $conversation->type);

        return [
            'action' => $action,
            'plan' => $plan,
            'signal' => $signal,
            'status' => 'queued',
        ];
    }
}
