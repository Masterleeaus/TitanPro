<?php

namespace App\Extensions\TitanOperator\System\Agent\Events;

use App\Extensions\TitanOperator\System\Http\Resources\Api\TitanOperatorHistoryResource;
use App\Extensions\TitanOperator\System\Models\TitanOperator;
use App\Extensions\TitanOperator\System\Models\TitanOperatorHistory;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TitanOperatorForPanelEvent implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public int $conversationId;

    public array $history;

    private string $eventChannel = 'panel-conversation-';

    public function __construct(TitanOperatorHistory $history, TitanOperator $titan_operator)
    {
        $this->conversationId = $history->getAttribute('conversation_id');
        $this->eventChannel .= $titan_operator->getAttribute('user_id');
        $this->history = TitanOperatorHistoryResource::make($history)->jsonSerialize();
    }

    public function broadcastOn(): Channel|array
    {
        return new Channel($this->eventChannel);
    }

    public function broadcastAs(): string
    {
        return 'new-message';
    }
}
