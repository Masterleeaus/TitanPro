<?php

namespace App\Extensions\TitanLeads\System\Services\Whatsapp;

use App\Extensions\TitanLeads\System\Enums\CampaignStatus;
use App\Extensions\TitanLeads\System\Models\MarketingCampaign;
use App\Extensions\TitanLeads\System\Models\MarketingMessageHistory;
use App\Extensions\TitanLeads\System\Models\Whatsapp\WhatsappChannel;
use App\Extensions\TitanLeads\System\Services\Generator\GeneratorService;
use Exception;

class WhatsappReplyService
{
    public WhatsappChannel $whatsappChannel;

    public function sendReply($request, $marketingConversation): void
    {
        $campaign = $this->findCampaign();

        if (! $campaign?->ai_reply) {
            return;
        }

        $whatsappSenderService = $this->whatsappSenderService();

        $whatsappSenderService->setWhatsappChannel(
            $this->whatsappChannel->getAttribute('user_id')
        );

        $response = $this->generateResponse(
            prompt: $request->get('Body'),
            marketingConversation: $marketingConversation,
            campaign: $campaign
        );

        $this->sendMessageToContact(
            conversation: $marketingConversation,
            content: $response
        );

        $data = $whatsappSenderService->sendText(
            receiver: $request->get('WaId'),
            message: $response,
        );
    }

    public function sendMessageToContact(
        $conversation,
        string $content,
    ): void {
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
                'created_at'      => now(),
            ]);
        } catch (Exception $exception) {
        }
    }

    protected function generateResponse(
        string $prompt,
        $marketingConversation,
        $campaign
    ): ?string {
        return app(GeneratorService::class)
            ->setMarketingCampaign($campaign)
            ->setConversation($marketingConversation)
            ->setPrompt($prompt)
            ->generate();
    }

    public function findCampaign()
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
