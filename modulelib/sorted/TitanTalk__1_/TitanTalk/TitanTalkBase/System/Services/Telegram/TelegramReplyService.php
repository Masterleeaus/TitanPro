<?php

namespace App\Extensions\MarketingBot\System\Services\Telegram;

use App\Extensions\MarketingBot\System\Enums\CampaignStatus;
use App\Extensions\MarketingBot\System\Models\MarketingCampaign;
use App\Extensions\MarketingBot\System\Models\MarketingMessageHistory;
use App\Extensions\MarketingBot\System\Models\Telegram\TelegramBot;
use App\Extensions\MarketingBot\System\Services\Conversation\AiChatbotService;
use App\Extensions\MarketingBot\System\Support\TitanTalkConfig;
use App\Extensions\MarketingBot\System\Services\Generator\GeneratorService;

class TelegramReplyService
{
    public TelegramBot $telegramBot;

    public function __construct(protected AiChatbotService $aiChatbotService) {}

    public function sendReply($request, $marketingConversation): void
    {
        if (! TitanTalkConfig::assistantEnabled()) {
            return;
        }

        $campaign = $this->findCampaign();
        if ($campaign && ! $campaign->ai_reply) {
            return;
        }

        $response = $campaign
            ? app(GeneratorService::class)
                ->setMarketingCampaign($campaign)
                ->setConversation($marketingConversation)
                ->setPrompt((string) $request->input('message.text'))
                ->generate()
            : $this->aiChatbotService->reply($marketingConversation, (string) $request->input('message.text'));

        if (! $response) {
            return;
        }

        MarketingMessageHistory::query()->create([
            'conversation_id' => $marketingConversation->getKey(),
            'message_id'      => random_int(100000000, 999999999),
            'model'           => null,
            'role'            => 'assistant',
            'message'         => $response,
            'type'            => 'default',
            'message_type'    => 'text',
            'content_type'    => 'text',
            'read_at'         => now(),
            'created_at'      => now(),
        ]);

        app(TelegramSenderService::class)
            ->setBot($this->telegramBot->getAttribute('user_id'))
            ->sendText(message: $response);
    }

    protected function findCampaign(): ?MarketingCampaign
    {
        return MarketingCampaign::query()
            ->where('ai_reply', true)
            ->orderBy('scheduled_at', 'desc')
            ->where('user_id', $this->telegramBot->getAttribute('user_id'))
            ->where('status', CampaignStatus::published->value)
            ->where('type', 'telegram')
            ->first();
    }

    public function setTelegramBot(TelegramBot $telegramBot): TelegramReplyService
    {
        $this->telegramBot = $telegramBot;

        return $this;
    }
}
