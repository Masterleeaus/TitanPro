<?php

namespace App\Extensions\TitanLeads\System\Http\Controllers\Webhook;

use App\Extensions\TitanLeads\System\Models\MarketingMessageHistory;
use App\Extensions\TitanLeads\System\Models\SmsChannel;
use App\Extensions\TitanLeads\System\Services\Sms\SmsSenderService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SmsWebhookController
{
    public function __invoke(Request $request, SmsSenderService $sms)
    {
        // Twilio default params: From, To, Body
        $to = $request->input('To');
        $from = $request->input('From');
        $body = $request->input('Body', '');

        $channel = SmsChannel::query()
            ->where('is_active', true)
            ->where(function ($q) use ($to) {
                $q->where('from_number', $to)
                  ->orWhere('from_number', Str::replaceFirst('+', '', (string)$to));
            })
            ->first();

        if (!$channel) {
            return response('SMS channel not configured', 404);
        }

        $conversation = $sms->updateOrCreateConversation((int)$channel->user_id, (string)$from);

        MarketingMessageHistory::query()->create([
            'conversation_id' => $conversation->getKey(),
            'message_id'      => random_int(100000000, 999999999),
            'model'           => null,
            'role'            => 'user',
            'message'         => (string)$body,
            'type'            => 'default',
            'message_type'    => 'text',
            'content_type'    => 'text',
            'created_at'      => now(),
        ]);

        $conversation->update(['last_activity_at' => now()]);

        return response('OK', 200);
    }
}
