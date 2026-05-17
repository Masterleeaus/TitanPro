<?php

namespace App\Extensions\TitanLeads\System\Services\Messenger;

use Illuminate\Support\Facades\Http;

class MessengerSenderService
{
    /**
     * Send a message via Meta Graph API (Facebook Messenger / Instagram Messaging).
     *
     * @param  string  $accessToken
     * @param  string  $recipientId  PSID (Messenger) or IG user id depending on integration
     * @param  string  $message
     * @param  string|null  $pageId  optional page id
     */
    public function send(string $accessToken, string $recipientId, string $message, ?string $pageId = null): array
    {
        // Meta Graph endpoint for messaging:
        // https://graph.facebook.com/vXX.X/me/messages?access_token=...
        // Using 'me' keeps it simple; page token determines context.
        $url = 'https://graph.facebook.com/v19.0/me/messages';

        $payload = [
            'messaging_type' => 'RESPONSE',
            'recipient' => ['id' => $recipientId],
            'message' => ['text' => $message],
        ];

        $resp = Http::asJson()->post($url, array_merge($payload, [
            'access_token' => $accessToken,
        ]));

        return [
            'ok' => $resp->successful(),
            'status' => $resp->status(),
            'body' => $resp->json(),
        ];
    }
}
