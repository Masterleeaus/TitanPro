<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Events\TitanTalk;

use App\Extensions\MarketingBot\System\Http\Resources\MarketingConversationResource;
use App\Extensions\MarketingBot\System\Models\MarketingConversation;
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

    public array $conversation;
    private string $eventChannel;

    public function __construct(MarketingConversation $conversation)
    {
        $this->eventChannel = 'titantalk-panel-new-conversation-' . $conversation->getAttribute('user_id');
        $this->conversation = MarketingConversationResource::make($conversation)->jsonSerialize();
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
