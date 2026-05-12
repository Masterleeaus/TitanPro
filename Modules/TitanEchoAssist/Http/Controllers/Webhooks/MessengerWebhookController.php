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

class MessengerWebhookController extends Controller
{
    /**
     * Facebook sends a GET request to verify the webhook endpoint.
     * Respond with the hub.challenge value when the verify token matches.
     */
    public function verify(Request $request, int $channelId): Response
    {
        $mode      = $request->query('hub_mode');
        $token     = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        $expectedToken = config('titan-chatbot.channels.messenger.verify_token', '');

        if ($mode === 'subscribe' && $token === $expectedToken) {
            return response((string) $challenge, 200);
        }

        return response('Forbidden', 403);
    }

    public function handle(Request $request, int $channelId): Response
    {
        // Blueprint 22: verify HMAC-SHA256 signature before processing any payload
        if (! $this->verifySignature($request)) {
            Log::warning('MessengerWebhook: invalid signature', [
                'channel_id' => $channelId,
                'ip'         => $request->ip(),
            ]);
            return response('Forbidden', 403);
        }

        try {
            $body = $request->all();

            if (($body['object'] ?? '') !== 'page') {
                return response('', 200);
            }

            foreach ($body['entry'] ?? [] as $entry) {
                foreach ($entry['messaging'] ?? [] as $event) {
                    $this->processEvent($event, $channelId);
                }
            }
        } catch (\Throwable $e) {
            Log::error('MessengerWebhook: failed', [
                'channel_id' => $channelId,
                'error'      => $e->getMessage(),
            ]);
        }

        return response('EVENT_RECEIVED', 200);
    }

    /**
     * Verify the X-Hub-Signature-256 header using HMAC-SHA256.
     *
     * Facebook signs the raw request body with the app secret and sends the
     * digest in the X-Hub-Signature-256 header as "sha256=<hex>".
     */
    private function verifySignature(Request $request): bool
    {
        $appSecret = config('titan-chatbot.channels.messenger.app_secret', '');

        // If no secret is configured we skip verification (dev/test environments)
        if ($appSecret === '') {
            return true;
        }

        $header = $request->header('X-Hub-Signature-256', '');

        if ($header === '') {
            return false;
        }

        $expected = 'sha256=' . hash_hmac('sha256', $request->getContent(), $appSecret);

        return hash_equals($expected, $header);
    }

    private function processEvent(array $event, int $channelId): void
    {
        $senderId = $event['sender']['id'] ?? 'unknown';
        $text     = $event['message']['text'] ?? '';

        if ($text === '') {
            return;
        }

        $payload = MessagePayload::fromArray([
            'chatbot_id' => $this->resolveChatbotId($channelId),
            'session_id' => (string) $senderId,
            'channel'    => 'messenger',
            'message'    => $text,
            'metadata'   => $event,
        ]);

        if (class_exists(ConversationThreadService::class)) {
            $thread = app(ConversationThreadService::class);
            $conversation = $thread->recordInbound('messenger', (string) $senderId, $text, $event);
            $reply = (string) app(ConversationRouter::class)->route($payload);
            $thread->recordOutbound($conversation, $reply, ['channel_id' => $channelId, 'source' => 'TitanEchoAssist']);
        } else {
            app(ConversationRouter::class)->route($payload);
        }
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
