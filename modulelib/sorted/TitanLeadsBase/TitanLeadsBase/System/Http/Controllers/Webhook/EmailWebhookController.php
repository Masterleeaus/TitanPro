<?php

namespace App\Extensions\TitanLeads\System\Http\Controllers\Webhook;

use App\Extensions\TitanLeads\System\Models\EmailChannel;
use App\Extensions\TitanLeads\System\Models\MarketingConversation;
use App\Extensions\TitanLeads\System\Models\MarketingMessageHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EmailWebhookController
{
    public function __invoke(Request $request)
    {
        // Expecting: to, from, subject, text/html body
        $to = $request->input('to') ?? $request->input('recipient');
        $from = $request->input('from') ?? $request->input('sender');
        $subject = $request->input('subject', '');
        $body = $request->input('text') ?? $request->input('body-plain') ?? $request->input('body', '');

        $channel = EmailChannel::query()->where('is_active', true)
            ->where(function ($q) use ($to) {
                $q->where('from_email', $to);
            })->first();

        if (!$channel) {
            return response('Email channel not configured', 404);
        }

        $sessionId = (string)$from;
        $conversation = MarketingConversation::query()->firstOrCreate([
            'user_id' => (int)$channel->user_id,
            'type' => 'email',
            'session_id' => $sessionId,
        ], [
            'conversation_name' => $sessionId,
            'customer_payload' => [
                'From' => $from,
                'To' => $to,
            ],
        ]);

        MarketingMessageHistory::query()->create([
            'conversation_id' => $conversation->getKey(),
            'message_id'      => random_int(100000000, 999999999),
            'model'           => null,
            'role'            => 'user',
            'message'         => trim($subject . "\n\n" . $body),
            'type'            => 'default',
            'message_type'    => 'text',
            'content_type'    => 'text',
            'created_at'      => now(),
        ]);

        $conversation->update(['last_activity_at' => now()]);

        return response('OK', 200);
    }
}
