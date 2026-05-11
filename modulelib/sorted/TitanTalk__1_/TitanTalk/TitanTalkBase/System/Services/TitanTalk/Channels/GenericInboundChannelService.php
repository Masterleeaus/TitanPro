<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Services\TitanTalk\Channels;

use App\Extensions\MarketingBot\System\Channels\ChannelRouter;
use App\Extensions\MarketingBot\System\Models\MarketingConversation;
use App\Extensions\MarketingBot\System\Services\Conversation\AiChatbotService;
use App\Extensions\MarketingBot\System\Support\TitanTalkConfig;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class GenericInboundChannelService
{
    public function __construct(
        protected ChannelRouter $channelRouter,
        protected ChannelMessageRecorder $recorder,
        protected AiChatbotService $aiChatbotService,
    ) {}

    /** @param array<string,mixed> $payload
     * @return array<string,mixed>
     */
    public function handle(string $channel, array $payload): array
    {
        $normalized = $this->channelRouter->normalize($channel, $payload);
        $conversation = $this->updateOrCreateConversation($channel, $normalized, $payload);

        if (($normalized['message_type'] ?? 'text') === 'text' && ! empty($normalized['message'])) {
            $this->recorder->recordInbound($conversation, $normalized);
            $reply = $this->aiChatbotService->replyWithContext($conversation, (string) $normalized['message']);

            return [
                'status' => 'processed',
                'channel' => $channel,
                'conversation_id' => $conversation->getKey(),
                'normalized' => $normalized,
                'reply' => $reply['reply'] ?? null,
                'command' => $reply['command'] ?? null,
                'signal' => $reply['signal'] ?? null,
                'context' => $reply['context'] ?? null,
            ];
        }

        return [
            'status' => 'ignored',
            'channel' => $channel,
            'conversation_id' => $conversation->getKey(),
            'normalized' => $normalized,
        ];
    }

    /** @param array<string,mixed> $normalized
     * @param array<string,mixed> $rawPayload
     */
    protected function updateOrCreateConversation(string $channel, array $normalized, array $rawPayload): Model|Builder
    {
        return MarketingConversation::query()->firstOrCreate([
            'user_id' => $rawPayload['user_id'] ?? null,
            'type' => $channel,
            'session_id' => (string) ($normalized['session_id'] ?? ''),
        ], [
            'conversation_name' => (string) ($normalized['conversation_name'] ?? ucfirst($channel) . ' Conversation'),
            'role_pack' => TitanTalkConfig::defaultRolePack(),
            'state' => 'new',
            'customer_payload' => $normalized['customer_payload'] ?? [],
        ]);
    }
}
