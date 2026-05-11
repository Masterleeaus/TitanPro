<?php

namespace Modules\TitanEchoAssist\Http\Controllers\Webhooks;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Modules\TitanEchoAssist\DTOs\MessagePayload;
use Modules\TitanEchoAssist\Services\ConversationRouter;

class WhatsappWebhookController extends Controller
{
    public function handle(Request $request, int $channelId): Response
    {
        try {
            $payload = MessagePayload::fromArray([
                'chatbot_id' => $this->resolveChatbotId($channelId),
                'session_id' => $request->input('WaId', $request->input('From', 'unknown')),
                'channel'    => 'whatsapp',
                'message'    => $request->input('Body', ''),
                'metadata'   => $request->all(),
            ]);

            app(ConversationRouter::class)->route($payload);
        } catch (\Throwable $e) {
            Log::error('WhatsappWebhook: failed', [
                'channel_id' => $channelId,
                'error'      => $e->getMessage(),
            ]);
        }

        // Twilio expects a 200 response (optionally with TwiML body)
        return response('', 200);
    }

    private function resolveChatbotId(int $channelId): int
    {
        try {
            if (class_exists(\Modules\TitanEchoAssist\Models\ChatbotChannel::class)) {
                $channel = \Modules\TitanEchoAssist\Models\ChatbotChannel::find($channelId);
                if ($channel) {
                    return (int) $channel->chatbot_id;
                }
            }
        } catch (\Throwable) {
            // Fall through to default
        }

        return $channelId;
    }
}
