<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Services\TitanTalk;

use App\Extensions\MarketingBot\System\Enums\ConversationIntent;

class RolePackResolverService
{
    public function resolve(ConversationIntent $intent): string
    {
        return match ($intent) {
            ConversationIntent::BOOKING, ConversationIntent::RESCHEDULE, ConversationIntent::CANCEL => 'titantalk.reception',
            ConversationIntent::QUOTE => 'titantalk.sales',
            ConversationIntent::INVOICE => 'titantalk.collections',
            ConversationIntent::SUPPORT, ConversationIntent::COMPLAINT => 'titantalk.support',
            ConversationIntent::HUMAN_HANDOFF => 'titantalk.operator',
            default => 'titantalk.general',
        };
    }
}
