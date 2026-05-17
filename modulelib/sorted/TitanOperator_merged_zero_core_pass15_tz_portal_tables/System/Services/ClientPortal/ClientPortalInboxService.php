<?php

namespace App\Extensions\TitanOperator\System\Services\ClientPortal;

use App\Extensions\TitanOperator\System\Models\TitanOperatorChannel;
use App\Extensions\TitanOperator\System\Models\TitanOperatorConversation;
use App\Extensions\TitanOperator\System\Models\TitanOperatorHistory;
use App\Extensions\TitanOperator\System\Models\TitanOperatorWorkflowRun;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class ClientPortalInboxService
{
    public function stats(): array
    {
        $userId = (int) Auth::id();

        $conversationBase = TitanOperatorConversation::query()->whereHas('titan_operator', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        });

        $historyBase = TitanOperatorHistory::query()->whereHas('conversation.titan_operator', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        });

        return [
            'open_conversations' => (clone $conversationBase)->where(function ($query) {
                $query->whereNull('ticket_status')->orWhere('ticket_status', 'open');
            })->count(),
            'pinned_conversations' => (clone $conversationBase)->where('pinned', true)->count(),
            'messages_today' => (clone $historyBase)->whereDate('created_at', now()->toDateString())->count(),
            'pending_workflows' => TitanOperatorWorkflowRun::query()
                ->whereHas('operator', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->whereIn('status', ['pending', 'proposed', 'queued'])
                ->count(),
        ];
    }

    public function recentConversations(int $limit = 8): Collection
    {
        return TitanOperatorConversation::query()
            ->with(['customer', 'operatorChannel', 'lastMessage'])
            ->whereHas('titan_operator', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->orderByDesc('last_activity_at')
            ->orderByDesc('id')
            ->limit($limit)
            ->get();
    }

    public function channelBreakdown(): Collection
    {
        return TitanOperatorChannel::query()
            ->selectRaw('channel, COUNT(*) as total')
            ->where('user_id', Auth::id())
            ->groupBy('channel')
            ->orderByDesc('total')
            ->get();
    }
}
