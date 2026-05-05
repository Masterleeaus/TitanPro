<?php

namespace Modules\TitanNexus\Services\Channels;

class TitanSmsChannelService
{
    public function normaliseInbound(array $payload): array
    {
        return [
            'channel' => 'sms',
            'from' => $payload['From'] ?? null,
            'to' => $payload['To'] ?? null,
            'message' => $payload['Body'] ?? '',
            'raw' => $payload,
        ];
    }
}
