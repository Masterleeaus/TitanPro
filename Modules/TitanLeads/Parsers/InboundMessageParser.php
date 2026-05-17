<?php

namespace Modules\TitanLeads\Parsers;

/**
 * Normalises inbound messages from any channel to a common lead-level payload.
 */
class InboundMessageParser
{
    /**
     * Parse a raw inbound payload from any supported channel.
     *
     * @param  string  $channel  One of: whatsapp|sms|telegram|messenger|voice
     * @param  array   $payload  Raw webhook payload
     * @return array{channel: string, from: string, body: string, raw: array}
     */
    public function parse(string $channel, array $payload): array
    {
        return match ($channel) {
            'whatsapp'  => $this->parseWhatsapp($payload),
            'sms'       => $this->parseSms($payload),
            'telegram'  => $this->parseTelegram($payload),
            'messenger' => $this->parseMessenger($payload),
            'voice'     => $this->parseVoice($payload),
            default     => ['channel' => $channel, 'from' => '', 'body' => '', 'raw' => $payload],
        };
    }

    private function parseWhatsapp(array $p): array
    {
        return [
            'channel' => 'whatsapp',
            'from'    => $p['From'] ?? $p['from'] ?? '',
            'body'    => $p['Body'] ?? $p['body'] ?? '',
            'raw'     => $p,
        ];
    }

    private function parseSms(array $p): array
    {
        return [
            'channel' => 'sms',
            'from'    => $p['From'] ?? $p['from'] ?? '',
            'body'    => $p['Body'] ?? $p['body'] ?? '',
            'raw'     => $p,
        ];
    }

    private function parseTelegram(array $p): array
    {
        $msg = $p['message'] ?? $p['edited_message'] ?? [];
        return [
            'channel' => 'telegram',
            'from'    => (string) ($msg['from']['id'] ?? ''),
            'body'    => $msg['text'] ?? '',
            'raw'     => $p,
        ];
    }

    private function parseMessenger(array $p): array
    {
        $entry   = $p['entry'][0] ?? [];
        $message = $entry['messaging'][0]['message'] ?? [];
        return [
            'channel' => 'messenger',
            'from'    => (string) ($entry['messaging'][0]['sender']['id'] ?? ''),
            'body'    => $message['text'] ?? '',
            'raw'     => $p,
        ];
    }

    private function parseVoice(array $p): array
    {
        return [
            'channel' => 'voice',
            'from'    => $p['From'] ?? $p['Caller'] ?? '',
            'body'    => $p['TranscriptionText'] ?? $p['SpeechResult'] ?? '',
            'raw'     => $p,
        ];
    }
}
