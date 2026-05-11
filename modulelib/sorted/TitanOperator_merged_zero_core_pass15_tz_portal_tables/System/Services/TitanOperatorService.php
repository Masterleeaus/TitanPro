<?php

namespace App\Extensions\TitanOperator\System\Services;

use App\Extensions\TitanOperator\System\Models\TitanOperator;
use App\Extensions\TitanOperator\System\Models\TitanOperatorAvatar;
use App\Extensions\TitanOperator\System\Models\TitanOperatorConversation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TitanOperatorService
{
    public function agentConversations(array $operators, ?string $orderBy = null): Collection|array
    {
        $agentFilter = filter_var(request()->get('agentFilter', false), FILTER_VALIDATE_BOOLEAN);

        return TitanOperatorConversation::query()
            ->where('is_showed_on_history', true)
            ->with('titan_operator:id,uuid,avatar,title')
            ->with(['histories.user:id,avatar', 'lastMessage'])
            ->when(($agentFilter == false), function (Builder $query) {
                $query->whereNull('connect_agent_at');
            }, function (Builder $query) {
                $query->whereNotNull('connect_agent_at');
            })
            ->whereIn('operator_id', $operators)
            ->orderBy('pinned', 'desc')
            ->when($orderBy, function (Builder $query) use ($orderBy) {
                $query->orderBy($orderBy ?: 'id', 'desc');
            })
            ->get();
    }

    public function unreadAgentMessagesCount(array $operators): int
    {
        return TitanOperatorConversation::query()
            ->whereNotNull('connect_agent_at')
            ->whereIn('operator_id', $operators)
            ->whereHas('histories', function (Builder $query) {
                $query->where('role', 'user')->where('read_at', null);
            })
            ->count();
    }

    public function unreadAiBotMessagesCount(array $operators): int
    {
        return TitanOperatorConversation::query()
            ->whereNull('connect_agent_at')
            ->whereIn('operator_id', $operators)
            ->whereHas('histories', function (Builder $query) {
                $query->where('role', 'user')->where('read_at', null);
            })
            ->count();
    }

    public function allMessagesCount(array $operators): int
    {
        return TitanOperatorConversation::query()
            ->whereIn('operator_id', $operators)
            ->whereHas('histories', function (Builder $query) {
                $query->where('role', 'user');
            })
            ->count();
    }

    public function historyConversationsWithPaginate(
        ?string $sessionId = null,
    ): LengthAwarePaginator {

        $sessionId = $sessionId ?: 0;

        return TitanOperatorConversation::query()
            ->where('session_id', $sessionId)
            ->where('is_showed_on_history', true)
            ->with('titan_operator:id,uuid,avatar')
            ->with(['histories.user:id,avatar', 'lastMessage'])
            ->whereNotNull('connect_agent_at')
            ->orderBy('last_activity_at', 'desc')
            ->paginate(request('per_page', request('perPage', 30)));
    }

    public function agentConversationsWithQuery(
        array $operators,
        ?string $orderBy = null,
    ): Builder|\Illuminate\Support\HigherOrderWhenProxy {
        $filterAgent = request('agentFilter');

        return TitanOperatorConversation::query()
            ->when(request('operator_channel') && request('operator_channel') !== 'all', function (Builder $query) {
                $query->where('operator_channel', request('operator_channel'));
            })
            ->where('is_showed_on_history', true)
            ->with('titan_operator:id,uuid,avatar,title')
            ->with(['histories.user:id,avatar', 'lastMessage'])
            ->when($filterAgent === 'ai', function (Builder $query) {
                $query->whereNotNull('connect_agent_at');
            })
            ->when($filterAgent === 'human', function (Builder $query) {
                $query->whereNull('connect_agent_at');
            })
            ->whereIn('operator_id', $operators);
    }

    public function agentConversationsWithPaginate(
        array $operators,
        ?string $orderBy = null,
    ): LengthAwarePaginator {
        $filterAgent = request('agentFilter');

        $ticketStatus = request('status');

        $unread = request('unread', false);

        $sort = request('sort', 'desc');

        return $this->agentConversationsWithQuery($operators, $orderBy)
            ->when($ticketStatus !== 'all' && in_array($ticketStatus, ['new', 'closed']), function (Builder $query) use ($ticketStatus) {
                $query->where('ticket_status', $ticketStatus);
            })
            ->orderBy('pinned', 'desc')
            ->when($unread === 'true', function (Builder $query) {
                $query->whereHas('histories', function (Builder $query) {
                    $query->where('role', 'user')
                        ->whereNull('read_at');
                });
            })
            ->when($sort === 'newest', function (Builder $query) {
                $query->orderBy(
                    function ($query) {
                        $query->select('created_at')
                            ->from('tz_portal_operator_histories')
                            ->whereColumn('tz_portal_operator_histories.conversation_id', 'tz_portal_operator_conversations.id')
                            ->where('tz_portal_operator_histories.role', 'user')
                            ->latest()
                            ->limit(1);
                    },
                    'desc'
                );
            })
            ->when($sort === 'oldest', function (Builder $query) {
                $query->orderBy(
                    function ($query) {
                        $query->select('created_at')
                            ->from('tz_portal_operator_histories')
                            ->whereColumn('tz_portal_operator_histories.conversation_id', 'tz_portal_operator_conversations.id')
                            ->where('tz_portal_operator_histories.role', 'user')
                            ->latest()
                            ->limit(1);
                    },
                    'asc'
                );
            })
            ->paginate(request('per_page', request('perPage', 30)));
    }

    public function agentConversationsBySearch(array $operators, string $search)
    {
        return TitanOperatorConversation::query()
            ->with('titan_operator:id,uuid,avatar,title')
            ->with(['histories.user:id,avatar', 'lastMessage'])
            ->whereNotNull('connect_agent_at')
            ->whereIn('operator_id', $operators)
            ->whereHas('histories', function (Builder $query) use ($search) {
                $query->where('message', 'like', "%$search%");
            })
            ->orderBy('pinned', 'desc')
            ->get();
    }

    public function conversations(array $operators, ?string $orderBy = null): Collection|array
    {
        return TitanOperatorConversation::query()
            ->where('is_showed_on_history', true)
            ->with('titan_operator:id,uuid,avatar,title')
            ->with(['histories', 'lastMessage'])
            ->whereIn('operator_id', $operators)
            ->when($orderBy, function (Builder $query) use ($orderBy) {
                $query->orderBy($orderBy ?: 'id', 'desc');
            })
            ->get();
    }

    public function conversationsWithPaginate(array $operators, ?string $orderBy = null): LengthAwarePaginator
    {
        $filterAgent = request('agentFilter');

        return TitanOperatorConversation::query()
            ->when(
                request('operator_channel') !== 'all',
                function (Builder $query) {
                    $query->where('operator_channel', request('operator_channel'));
                }
            )
            ->where('is_showed_on_history', true)
            ->with('titan_operator:id,uuid,avatar,title')
            ->with(['histories', 'lastMessage'])
            ->when($filterAgent === 'ai', function (Builder $query) {
                $query->whereNotNull('connect_agent_at');
            })
            ->when($filterAgent === 'human', function (Builder $query) {
                $query->whereNull('connect_agent_at');
            })
            ->whereIn('operator_id', $operators)

            ->when(request('unread') === 'true' || request('unread') === true, function (Builder $query) {
                $query->whereHas('lastMessage', function ($q) {
                    $q->whereNull('read_at');
                });
            })

            ->when(request('sort'), function (Builder $query) {
                $direction = request('sort') === 'oldest' ? 'asc' : 'desc';
                $query->orderBy('id', $direction);
            }, function (Builder $query) use ($orderBy) {
                // fallback if no "sort" is provided
                $query->orderBy($orderBy ?: 'id', 'desc');
            })

            ->paginate(request('per_page', request('perPage', 30)));
    }

    public function update(Model|int $model, array $data): Model
    {
        if (is_int($model)) {
            $model = $this->query()->findOrFail($model);
        }

        $model->update($data);

        return $model;
    }

    public function avatars(): Collection|array
    {
        return TitanOperatorAvatar::query()
            ->where(function (Builder $query) {
                return $query->where('user_id', Auth::id())->orWhereNull('user_id');
            })
            ->get();
    }

    public function query(): \Illuminate\Database\Eloquent\Builder
    {
        return TitanOperator::query();
    }
}
