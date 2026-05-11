<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Services\TitanTalk\Channels;

class TelegramPayloadNormalizer implements ChannelPayloadNormalizerInterface
{
    public function normalize(array $payload): array
    {
        $message = $payload['message'] ?? [];
        $chat = $message['chat'] ?? [];

        return [
            'channel' => 'telegram',
            'session_id' => (string) ($chat['id'] ?? ''),
            'message_id' => (string) ($message['message_id'] ?? ''),
            'message' => trim((string) ($message['text'] ?? '')),
            'message_type' => 'text',
            'conversation_name' => (string) ($chat['title'] ?? $chat['username'] ?? 'Telegram'),
            'customer_payload' => $message,
        ];
    }
}
