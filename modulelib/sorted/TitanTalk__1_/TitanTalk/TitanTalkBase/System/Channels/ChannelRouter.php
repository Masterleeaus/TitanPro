<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Channels;

use App\Extensions\MarketingBot\System\Channels\Adapters\EmailAdapter;
use App\Extensions\MarketingBot\System\Channels\Adapters\SmsAdapter;
use App\Extensions\MarketingBot\System\Services\TitanTalk\Channels\TelegramPayloadNormalizer;
use App\Extensions\MarketingBot\System\Services\TitanTalk\Channels\WhatsappPayloadNormalizer;

class ChannelRouter
{
    public function __construct(
        protected WhatsappPayloadNormalizer $whatsapp,
        protected TelegramPayloadNormalizer $telegram,
        protected EmailAdapter $email,
        protected SmsAdapter $sms,
    ) {}

    /** @param array<string,mixed> $payload
     *  @return array<string,mixed>
     */
    public function normalize(string $channel, array $payload): array
    {
        return match ($channel) {
            'whatsapp' => $this->whatsapp->normalize($payload),
            'telegram' => $this->telegram->normalize($payload),
            'email' => $this->email->normalizeInbound($payload),
            'sms' => $this->sms->normalizeInbound($payload),
            default => [
                'session_id' => (string) ($payload['session_id'] ?? ''),
                'message' => (string) ($payload['message'] ?? $payload['text'] ?? ''),
                'message_type' => (string) ($payload['message_type'] ?? 'text'),
                'conversation_name' => (string) ($payload['conversation_name'] ?? ucfirst($channel) . ' Conversation'),
                'customer_payload' => $payload,
            ],
        };
    }

    /** @return array<string,string> */
    public function supported(): array
    {
        return [
            'whatsapp' => 'WhatsApp',
            'telegram' => 'Telegram',
            'voice' => 'Voice',
            'webchat' => 'Web Chat',
            'email' => 'Email',
            'sms' => 'SMS',
        ];
    }
}
