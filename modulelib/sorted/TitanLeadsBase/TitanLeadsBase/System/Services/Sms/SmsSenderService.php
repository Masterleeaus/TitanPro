<?php

namespace App\Extensions\TitanLeads\System\Services\Sms;

use App\Extensions\TitanLeads\System\Models\MarketingConversation;
use App\Extensions\TitanLeads\System\Models\MarketingMessageHistory;
use App\Extensions\TitanLeads\System\Models\SmsChannel;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Twilio\Rest\Client;

class SmsSenderService
{
    public ?SmsChannel $smsChannel = null;

    public function setSmsChannel(int $userId): self
    {
        $this->smsChannel = SmsChannel::query()->where('user_id', $userId)->where('is_active', true)->first();
        return $this;
    }

    public function sendText(string $receiver, string $message): array
    {
        if (!$this->smsChannel) {
            throw new Exception('SMS channel not configured for this user');
        }

        $client = new Client($this->smsChannel->account_sid, $this->smsChannel->auth_token);

        $to = $this->normalizeE164($receiver);
        $from = $this->normalizeE164($this->smsChannel->from_number ?? '');

        $twMsg = $client->messages->create($to, [
            'from' => $from,
            'body' => $message,
        ]);

        return [
            'status' => true,
            'sid' => $twMsg->sid,
        ];
    }

    public function updateOrCreateConversation(int $userId, string $phone): Model|Builder
    {
        return MarketingConversation::query()->firstOrCreate([
            'user_id' => $userId,
            'type' => 'sms',
            'session_id' => Str::replaceFirst('+', '', $this->normalizeE164($phone)),
        ], [
            'conversation_name' => Str::replaceFirst('+', '', $this->normalizeE164($phone)),
            'customer_payload' => [
                'From' => $this->normalizeE164($phone),
            ],
        ]);
    }

    public function logOutbound(Model $conversation, string $message): void
    {
        MarketingMessageHistory::query()->create([
            'conversation_id' => $conversation->getKey(),
            'message_id' => random_int(100000000, 999999999),
            'model' => null,
            'role' => 'assistant',
            'message' => $message,
            'type' => 'default',
            'message_type' => 'text',
            'content_type' => 'text',
            'read_at' => now(),
            'created_at' => now(),
        ]);
    }

    private function normalizeE164(string $number): string
    {
        $cleaned = preg_replace('/[\s\-\(\)]+/', '', $number);
        if ($cleaned === null) {
            return $number;
        }
        if ($cleaned === '') {
            return '';
        }
        if (str_starts_with($cleaned, '+')) {
            return $cleaned;
        }
        return '+' . $cleaned;
    }
}
