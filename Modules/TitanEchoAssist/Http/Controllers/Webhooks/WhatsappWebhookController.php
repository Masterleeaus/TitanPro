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
        // Blueprint 22: verify Twilio HMAC-SHA1 signature before processing
        if (! $this->verifySignature($request)) {
            Log::warning('WhatsappWebhook: invalid signature', [
                'channel_id' => $channelId,
                'ip'         => $request->ip(),
            ]);
            return response('Forbidden', 403);
        }

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

    /**
     * Verify the Twilio HMAC-SHA1 webhook signature.
     *
     * Twilio signs each request using the auth token and the full URL including
     * sorted POST parameters. The resulting base64-encoded SHA-1 HMAC is sent in
     * the X-Twilio-Signature header.
     */
    private function verifySignature(Request $request): bool
    {
        $authToken = config('titan-chatbot.channels.whatsapp.auth_token', '');

        // If no token is configured we skip verification (dev/test environments)
        if ($authToken === '') {
            return true;
        }

        $twilioSignature = $request->header('X-Twilio-Signature', '');

        if ($twilioSignature === '') {
            return false;
        }

        // Build the string to sign: URL + sorted POST params concatenated
        $url        = $request->fullUrl();
        $postParams = $request->post() ?? [];
        ksort($postParams);

        $signingString = $url;
        foreach ($postParams as $key => $value) {
            $signingString .= $key . $value;
        }

        $expected = base64_encode(hash_hmac('sha1', $signingString, $authToken, true));

        return hash_equals($expected, $twilioSignature);
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
