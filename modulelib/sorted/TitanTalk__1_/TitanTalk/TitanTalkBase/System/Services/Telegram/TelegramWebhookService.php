<?php

namespace App\Extensions\MarketingBot\System\Services\Telegram;

use App\Extensions\MarketingBot\System\Models\MarketingConversation;
use App\Extensions\MarketingBot\System\Models\Telegram\TelegramBot;
use App\Extensions\MarketingBot\System\Services\TitanTalk\Channels\ChannelMessageRecorder;
use App\Extensions\MarketingBot\System\Services\TitanTalk\Channels\TelegramPayloadNormalizer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class TelegramWebhookService
{
    use Traits\HasGetTelegramGroup;

    public function __construct(
        protected TelegramPayloadNormalizer $normalizer,
        protected ChannelMessageRecorder $recorder,
    ) {}

    public function handle(Request $request, TelegramBot $telegramBot): void
    {
        $normalized = $this->normalizer->normalize($request->all());
        $marketingConversation = $this->updateOrCreateMarketingConversation($normalized, $telegramBot);

        if (! empty($normalized['message'])) {
            $this->recorder->recordInbound($marketingConversation, $normalized);

            app(TelegramReplyService::class)
                ->setTelegramBot($telegramBot)
                ->sendReply($request, $marketingConversation);
        }
    }

    /**
     * @param array<string,mixed> $normalized
     */
    public function updateOrCreateMarketingConversation(array $normalized, TelegramBot $telegramBot): Model|Builder
    {
        $telegramBotId = $telegramBot->getKey();
        $chatId = (string) ($normalized['session_id'] ?? '');
        $supergroupSubscriberId = $chatId . '-' . $telegramBotId;

        return MarketingConversation::query()
            ->updateOrCreate([
                'user_id' => $telegramBot->getAttribute('user_id'),
                'type' => 'telegram',
                'session_id' => $supergroupSubscriberId,
                'telegram_group_id' => $telegramBot->getKey(),
            ], [
                'conversation_name' => (string) ($normalized['conversation_name'] ?? 'Telegram Group'),
                'customer_payload' => $normalized['customer_payload'] ?? [],
            ]);
    }
}
