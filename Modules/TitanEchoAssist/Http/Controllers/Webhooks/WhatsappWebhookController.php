<?php

namespace Modules\TitanEchoAssist\Http\Controllers\Webhooks;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Modules\TitanEchoAssist\DTOs\MessagePayload;
use Modules\TitanEchoAssist\Services\ConversationRouter;
use Modules\TitanTalk\Services\ConversationThreadService;

class WhatsappWebhookController extends Controller
{
    public function handle(Request $request, int $channelId): Response
    {
        try {
            $sessionId = (string) $request->input('WaId', $request->input('From', 'unknown'));
            $incoming = (string) $request->input('Body', '');

            $payload = MessagePayload::fromArray([
                'chatbot_id' => $this->resolveChatbotId($channelId),
                'session_id' => $sessionId,
                'channel'    => 'whatsapp',
                'message'    => $incoming,
                'metadata'   => $request->all(),
            ]);

            if (class_exists(ConversationThreadService::class)) {
                $thread = app(ConversationThreadService::class);
                $conversation = $thread->recordInbound('whatsapp', $sessionId, $incoming, $request->all());
                $reply = (string) app(ConversationRouter::class)->route($payload);
                $thread->recordOutbound($conversation, $reply, ['channel_id' => $channelId, 'source' => 'TitanEchoAssist']);
            } else {
                app(ConversationRouter::class)->route($payload);
            }
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
