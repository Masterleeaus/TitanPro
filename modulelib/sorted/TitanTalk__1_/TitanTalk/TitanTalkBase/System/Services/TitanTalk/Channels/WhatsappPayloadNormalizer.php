<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Services\TitanTalk\Channels;

class WhatsappPayloadNormalizer implements ChannelPayloadNormalizerInterface
{
    public function normalize(array $payload): array
    {
        return [
            'channel' => 'whatsapp',
            'session_id' => (string) ($payload['WaId'] ?? ''),
            'message_id' => (string) ($payload['SmsSid'] ?? ''),
            'message' => trim((string) ($payload['Body'] ?? '')),
            'message_type' => (string) ($payload['MessageType'] ?? 'text'),
            'conversation_name' => (string) ($payload['ProfileName'] ?? $payload['WaId'] ?? 'Number'),
            'customer_payload' => [
                'AccountSid' => $payload['AccountSid'] ?? null,
                'From' => $payload['From'] ?? null,
            ],
        ];
    }
}
