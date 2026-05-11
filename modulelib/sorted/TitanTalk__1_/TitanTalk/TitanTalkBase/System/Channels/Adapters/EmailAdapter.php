<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Channels\Adapters;

use App\Extensions\MarketingBot\System\Channels\Contracts\ChannelAdapter;

class EmailAdapter implements ChannelAdapter
{
    public function key(): string
    {
        return 'email';
    }

    /** @param array<string,mixed> $payload
     * @return array<string,mixed>
     */
    public function normalizeInbound(array $payload): array
    {
        $participant = $this->extractParticipant($payload);
        $sessionId = (string) ($payload['session_id'] ?? $payload['thread_id'] ?? $payload['conversation_id'] ?? $participant['id'] ?? '');

        return [
            'session_id' => $sessionId,
            'message_id' => (string) ($payload['message_id'] ?? $payload['id'] ?? ''),
            'message' => (string) ($payload['message'] ?? $payload['text'] ?? $payload['body'] ?? $payload['content'] ?? ''),
            'message_type' => (string) ($payload['message_type'] ?? 'text'),
            'conversation_name' => (string) ($payload['conversation_name'] ?? ($participant['name'] ?: 'Email Conversation')),
            'customer_payload' => array_merge($payload, [
                'participant' => $participant,
                'channel' => 'email',
            ]),
        ];
    }

    /** @param array<string,mixed> $payload
     * @return array<string,mixed>
     */
    public function sendMessage(array $payload): array
    {
        return [
            'channel' => 'email',
            'status' => 'queued',
            'recipient' => $payload['to'] ?? $payload['participant']['id'] ?? null,
            'message' => (string) ($payload['message'] ?? ''),
            'provider' => 'email-stub',
        ];
    }

    /** @param array<string,mixed> $payload
     * @return array<string,mixed>
     */
    public function extractParticipant(array $payload): array
    {
        return [
            'id' => (string) ($payload['from'] ?? $payload['email'] ?? $payload['phone'] ?? $payload['sender'] ?? ''),
            'name' => (string) ($payload['from_name'] ?? $payload['name'] ?? $payload['sender_name'] ?? ''),
            'email' => (string) ($payload['email'] ?? ''),
            'phone' => (string) ($payload['phone'] ?? $payload['from'] ?? ''),
        ];
    }
}
