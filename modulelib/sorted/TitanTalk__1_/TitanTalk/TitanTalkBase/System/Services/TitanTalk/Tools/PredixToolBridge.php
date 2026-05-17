<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Services\TitanTalk\Tools;

use App\Extensions\MarketingBot\System\Models\MarketingConversation;

class PredixToolBridge
{
    /**
     * @param array<string,mixed> $tool
     * @param array<string,mixed> $arguments
     * @return array<string,mixed>
     */
    public function plan(MarketingConversation $conversation, array $tool, array $arguments = []): array
    {
        return [
            'bridge' => 'predix',
            'conversation_id' => $conversation->getKey(),
            'tool' => $tool['name'] ?? 'unknown',
            'mode' => $tool['mode'] ?? 'read',
            'approval_required' => (bool) ($tool['approval_required'] ?? false),
            'arguments' => $arguments,
            'status' => 'planned',
        ];
    }
}
