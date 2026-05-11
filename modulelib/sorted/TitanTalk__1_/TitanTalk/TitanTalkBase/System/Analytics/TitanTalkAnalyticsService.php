<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Analytics;

use App\Extensions\MarketingBot\System\Models\MarketingConversation;
use App\Extensions\MarketingBot\System\Models\MarketingMessageHistory;
use App\Extensions\MarketingBot\System\Models\TitanTalk\Handoff;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class TitanTalkAnalyticsService
{
    /** @return array<string,mixed> */
    public function overview(?int $userId = null): array
    {
        $windowDays = (int) config('titantalk.analytics_window_days', 14);
        $since = Carbon::now()->subDays($windowDays);

        $conversationQuery = MarketingConversation::query()->where('created_at', '>=', $since);
        $messageQuery = MarketingMessageHistory::query()->where('created_at', '>=', $since);
        $handoffQuery = Handoff::query()->where('created_at', '>=', $since);

        if ($userId !== null) {
            $conversationQuery->where('user_id', $userId);
            $messageQuery->where('user_id', $userId);
            $handoffQuery->whereHas('conversation', fn ($q) => $q->where('user_id', $userId));
        }

        return [
            'window_days' => $windowDays,
            'since' => $since->toDateTimeString(),
            'conversation_total' => (clone $conversationQuery)->count(),
            'message_total' => (clone $messageQuery)->count(),
            'handoff_open' => (clone $handoffQuery)->whereIn('state', ['open','assigned'])->count(),
            'handoff_total' => (clone $handoffQuery)->count(),
            'by_channel' => (clone $conversationQuery)
                ->selectRaw('type, count(*) as aggregate')
                ->groupBy('type')
                ->pluck('aggregate', 'type')
                ->toArray(),
            'by_state' => (clone $conversationQuery)
                ->selectRaw('state, count(*) as aggregate')
                ->groupBy('state')
                ->pluck('aggregate', 'state')
                ->toArray(),
            'by_intent' => (clone $conversationQuery)
                ->whereNotNull('intent')
                ->selectRaw('intent, count(*) as aggregate')
                ->groupBy('intent')
                ->pluck('aggregate', 'intent')
                ->toArray(),
            'recent_conversations' => (clone $conversationQuery)
                ->latest('id')
                ->limit(8)
                ->get(['id','conversation_name','type','intent','state','goal','last_activity_at'])
                ->toArray(),
        ];
    }
}
