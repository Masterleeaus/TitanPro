<?php

namespace Modules\TitanHello\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\TitanHello\Models\Call;

class CallStatusUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public readonly Call $call) {}

    public function broadcastOn(): Channel
    {
        return new Channel('titanhello.calls');
    }

    public function broadcastAs(): string
    {
        return 'call.status.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->call->id,
            'company_id' => $this->call->company_id,
            'status' => $this->call->status,
            'direction' => $this->call->direction,
            'duration_seconds' => $this->call->duration_seconds,
            'provider_call_sid' => $this->call->provider_call_sid,
        ];
    }
}
