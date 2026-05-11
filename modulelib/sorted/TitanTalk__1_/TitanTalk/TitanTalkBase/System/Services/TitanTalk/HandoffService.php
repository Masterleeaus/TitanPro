<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Services\TitanTalk;

use App\Extensions\MarketingBot\System\Models\MarketingConversation;
use App\Extensions\MarketingBot\System\Models\TitanTalk\Handoff;

class HandoffService
{
    public function queue(MarketingConversation $conversation, string $reason, array $context = []): Handoff
    {
        $existing = Handoff::query()
            ->where('conversation_id', $conversation->getKey())
            ->whereIn('state', ['open', 'assigned'])
            ->latest('id')
            ->first();

        if ($existing) {
            $existing->fill([
                'reason' => $reason,
                'intent' => $conversation->intent,
                'goal' => $conversation->goal,
                'priority' => $this->priorityFor($conversation),
                'context' => array_merge($existing->context ?? [], $context),
                'requested_at' => $existing->requested_at ?: now(),
            ])->save();

            return $existing->refresh();
        }

        return Handoff::query()->create([
            'conversation_id' => $conversation->getKey(),
            'company_id' => $conversation->user_id,
            'channel' => $conversation->type,
            'intent' => $conversation->intent,
            'goal' => $conversation->goal,
            'reason' => $reason,
            'context' => $context,
            'priority' => $this->priorityFor($conversation),
            'state' => 'open',
            'requested_at' => now(),
        ]);
    }

    protected function priorityFor(MarketingConversation $conversation): string
    {
        return match ((string) $conversation->intent) {
            'complaint' => 'high',
            'human_handoff' => 'high',
            'invoice' => 'normal',
            default => 'normal',
        };
    }
}
