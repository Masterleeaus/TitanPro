<?php

namespace Modules\TitanEchoAssist\Http\Controllers\Webhooks;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Modules\TitanEchoAssist\DTOs\MessagePayload;
use Modules\TitanEchoAssist\Services\ConversationRouter;
use Modules\TitanTalk\Services\ConversationThreadService;

class TelegramWebhookController extends Controller
{
    public function handle(Request $request, int $channelId): Response
    {
        // Blueprint 22: verify Telegram secret token before processing
        if (! $this->verifySecretToken($request)) {
            Log::warning('TelegramWebhook: invalid secret token', [
                'channel_id' => $channelId,
                'ip'         => $request->ip(),
            ]);
            return response('Forbidden', 403);
        }

        try {
            $update  = $request->all();
            $message = $update['message'] ?? $update['edited_message'] ?? null;

            if (! $message) {
                return response('', 200);
            }

            $sessionId = (string) ($message['from']['id'] ?? 'unknown');
            $text      = $message['text'] ?? '';

            $payload = MessagePayload::fromArray([
                'chatbot_id' => $this->resolveChatbotId($channelId),
                'session_id' => $sessionId,
                'channel'    => 'telegram',
                'message'    => $text,
                'metadata'   => $update,
            ]);

            if (class_exists(ConversationThreadService::class)) {
                $thread = app(ConversationThreadService::class);
                $conversation = $thread->recordInbound('telegram', $sessionId, (string) $text, $update);
                $reply = (string) app(ConversationRouter::class)->route($payload);
                $thread->recordOutbound($conversation, $reply, ['channel_id' => $channelId, 'source' => 'TitanEchoAssist']);
            } else {
                app(ConversationRouter::class)->route($payload);
            }
        } catch (\Throwable $e) {
            Log::error('TelegramWebhook: failed', [
                'channel_id' => $channelId,
                'error'      => $e->getMessage(),
            ]);
        }

        return response('', 200);
    }

    /**
     * Respond to Telegram's webhook verification (not required by Telegram, kept for tooling).
     */
    public function verify(Request $request, int $channelId): JsonResponse
    {
        return response()->json(['ok' => true, 'channel_id' => $channelId]);
    }

    /**
     * Verify the X-Telegram-Bot-Api-Secret-Token header.
     *
     * When registering a Telegram webhook with setWebhook, a secret_token can be
     * provided. Telegram sends this token in the X-Telegram-Bot-Api-Secret-Token
     * header with every update. Requests missing or carrying the wrong token are rejected.
     */
    private function verifySecretToken(Request $request): bool
    {
        $expectedToken = config('titan-chatbot.channels.telegram.webhook_secret', '');

        // If no secret is configured we skip verification (dev/test environments)
        if ($expectedToken === '') {
            return true;
        }

        $incomingToken = $request->header('X-Telegram-Bot-Api-Secret-Token', '');

        if ($incomingToken === '') {
            return false;
        }

        return hash_equals($expectedToken, $incomingToken);
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
