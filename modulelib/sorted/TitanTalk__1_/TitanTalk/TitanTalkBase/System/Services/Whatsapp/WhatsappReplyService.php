<?php

namespace App\Extensions\MarketingBot\System\Services\Whatsapp;

use App\Extensions\MarketingBot\System\Enums\CampaignStatus;
use App\Extensions\MarketingBot\System\Models\MarketingCampaign;
use App\Extensions\MarketingBot\System\Models\MarketingMessageHistory;
use App\Extensions\MarketingBot\System\Models\Whatsapp\WhatsappChannel;
use App\Extensions\MarketingBot\System\Services\Conversation\AiChatbotService;
use App\Extensions\MarketingBot\System\Support\TitanTalkConfig;
use App\Extensions\MarketingBot\System\Services\Generator\GeneratorService;
use Exception;

class WhatsappReplyService
{
    public WhatsappChannel $whatsappChannel;

    public function __construct(protected AiChatbotService $aiChatbotService) {}

    public function sendReply($request, $marketingConversation): void
    {
        if (! TitanTalkConfig::assistantEnabled()) {
            return;
        }

        $campaign = $this->findCampaign();

        if ($campaign && ! $campaign?->ai_reply) {
            return;
        }

        $whatsappSenderService = $this->whatsappSenderService();

        $whatsappSenderService->setWhatsappChannel(
            $this->whatsappChannel->getAttribute('user_id')
        );

        $response = $this->generateResponse(
            prompt: (string) $request->get('Body'),
            marketingConversation: $marketingConversation,
            campaign: $campaign
        );

        $this->sendMessageToContact(
            conversation: $marketingConversation,
            content: $response
        );

        $whatsappSenderService->sendText(
            receiver: $request->get('WaId'),
            message: $response,
        );
    }

    public function sendMessageToContact($conversation, string $content): void
    {
        try {
            MarketingMessageHistory::query()->create([
                'conversation_id' => $conversation->getKey(),
                'message_id'      => random_int(100000000, 999999999),
                'model'           => null,
                'role'            => 'assistant',
                'message'         => $content,
                'media_url'       => null,
                'type'            => 'default',
                'message_type'    => 'text',
                'content_type'    => 'text',
                'read_at'         => now(),
                'created_at'      => now(),
            ]);
        } catch (Exception $exception) {
        }
    }

    protected function generateResponse(string $prompt, $marketingConversation, $campaign): ?string
    {
        if ($campaign) {
            return app(GeneratorService::class)
                ->setMarketingCampaign($campaign)
                ->setConversation($marketingConversation)
                ->setPrompt($prompt)
                ->generate();
        }

        return $this->aiChatbotService->reply($marketingConversation, $prompt);
    }

    public function findCampaign(): ?MarketingCampaign
    {
        return MarketingCampaign::query()
            ->where('ai_reply', true)
            ->orderBy('scheduled_at', 'desc')
            ->where('user_id', $this->whatsappChannel->getAttribute('user_id'))
            ->where('status', CampaignStatus::published->value)
            ->where('type', 'whatsapp')
            ->first();
    }

    public function setWhatsappChannel(WhatsappChannel $whatsappChannel): WhatsappReplyService
    {
        $this->whatsappChannel = $whatsappChannel;

        return $this;
    }

    public function whatsappSenderService()
    {
        return app(WhatsappSenderService::class);
    }
}
