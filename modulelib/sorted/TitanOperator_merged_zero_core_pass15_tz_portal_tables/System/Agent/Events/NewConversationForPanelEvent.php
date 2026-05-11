<?php

namespace App\Extensions\TitanOperator\System\Agent\Events;

use App\Extensions\TitanOperator\System\Http\Resources\Admin\TitanOperatorConversationResource;
use App\Extensions\TitanOperator\System\Models\TitanOperator;
use App\Extensions\TitanOperator\System\Models\TitanOperatorConversation;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewConversationForPanelEvent implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public array $operatorConversation;

    private string $eventChannel = 'panel-new-conversation-';

    public function __construct(TitanOperator $titan_operator, TitanOperatorConversation $operatorConversation)
    {
        $this->eventChannel .= $titan_operator->getAttribute('user_id');

        $this->operatorConversation = TitanOperatorConversationResource::make($operatorConversation)->jsonSerialize();
    }

    public function broadcastOn(): Channel|array
    {
        return new Channel($this->eventChannel);
    }

    public function broadcastAs(): string
    {
        return 'new-conversation';
    }
}
