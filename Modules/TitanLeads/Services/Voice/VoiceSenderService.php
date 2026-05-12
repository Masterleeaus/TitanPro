<?php

namespace Modules\TitanLeads\Services\Voice;

use Modules\TitanLeads\Models\VoiceChannel;
use Exception;
use Twilio\Rest\Client;

class VoiceSenderService
{
    public ?VoiceChannel $voiceChannel = null;

    public function setVoiceChannel(int $userId): self
    {
        $this->voiceChannel = VoiceChannel::query()->where('user_id', $userId)->where('is_active', true)->first();
        return $this;
    }

    public function callAndSpeak(string $to, string $text, ?string $statusCallback = null): array
    {
        if (!$this->voiceChannel) {
            throw new Exception('Voice channel not configured for this user');
        }

        $client = new Client($this->voiceChannel->account_sid, $this->voiceChannel->auth_token);

        $from = $this->normalizeE164($this->voiceChannel->from_number ?? '');
        $to = $this->normalizeE164($to);

        $twiml = '<Response><Say>' . htmlspecialchars($text, ENT_QUOTES | ENT_XML1, 'UTF-8') . '</Say></Response>';

        $payload = [
            'twiml' => $twiml,
            'from' => $from,
        ];

        if ($statusCallback) {
            $payload['statusCallback'] = $statusCallback;
            $payload['statusCallbackEvent'] = ['initiated', 'ringing', 'answered', 'completed'];
        }

        $call = $client->calls->create($to, $from, $payload);

        return ['status' => true, 'sid' => $call->sid];
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
