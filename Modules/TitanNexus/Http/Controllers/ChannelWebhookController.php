<?php

namespace Modules\TitanNexus\Http\Controllers;

class ChannelWebhookController
{
    public function receive(array $payload): array
    {
        return [
            'ok' => true,
            'received' => true,
            'channel' => $payload['channel'] ?? 'unknown',
            'next' => 'dispatch_to_channel_pipeline',
        ];
    }
}
