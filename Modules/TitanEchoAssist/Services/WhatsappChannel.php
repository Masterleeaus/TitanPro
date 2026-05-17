<?php

namespace Modules\TitanEchoAssist\Services;

use Illuminate\Support\Facades\Log;
use Modules\TitanEchoAssist\Contracts\ChannelDriver;

class WhatsappChannel implements ChannelDriver
{
    public function handle(array $payload): string
    {
        // Delegate to legacy TwilioConversationService when available
        if (class_exists(\Modules\TitanEchoAssist\Channels\WhatsApp\System\Services\Twillio\TwilioConversationService::class)) {
            return $this->handleViaTwilio($payload);
        }

        return $this->handleDirect($payload);
    }

    private function handleViaTwilio(array $payload): string
    {
        try {
            /** @var \Modules\TitanEchoAssist\Channels\WhatsApp\System\Services\Twillio\TwilioConversationService $service */
            $service = app(\Modules\TitanEchoAssist\Channels\WhatsApp\System\Services\Twillio\TwilioConversationService::class);

            $service->setChatbotId((int) ($payload['chatbot_id'] ?? 0));
            $service->setPayload($payload['metadata'] ?? $payload);

            if (isset($payload['channel_id'])) {
                $service->setChannelId((int) $payload['channel_id']);
            }

            $conversation = $service->storeConversation();
            $service->storeHistory($conversation);
            $service->handleWhatsapp();

            return 'ok';
        } catch (\Throwable $e) {
            Log::error('WhatsappChannel: Twilio delegate failed.', ['error' => $e->getMessage()]);

            return $this->handleDirect($payload);
        }
    }

    private function handleDirect(array $payload): string
    {
        $message = $payload['message'] ?? '';

        /** @var GeneratorBridge $generator */
        $generator = app(GeneratorBridge::class);

        return $generator
            ->setChatbot($payload['chatbot'] ?? null)
            ->generate($message);
    }
}
