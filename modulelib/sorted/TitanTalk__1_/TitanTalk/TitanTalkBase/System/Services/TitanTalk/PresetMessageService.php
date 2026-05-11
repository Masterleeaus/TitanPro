<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Services\TitanTalk;

use App\Extensions\MarketingBot\System\Models\MarketingConversation;
use App\Extensions\MarketingBot\System\Models\MarketingMessageHistory;
use App\Extensions\MarketingBot\System\Services\Telegram\TelegramSenderService;
use App\Extensions\MarketingBot\System\Services\Whatsapp\WhatsappSenderService;
use App\Extensions\MarketingBot\System\Signals\TitanTalkSignalBridge;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class PresetMessageService
{
    public function __construct(
        protected WhatsappSenderService $whatsappSenderService,
        protected TelegramSenderService $telegramSenderService,
        protected TitanTalkSignalBridge $signalBridge,
    ) {}

    /** @return array<string,array<string,mixed>> */
    public function presets(): array
    {
        return [
            'running_late' => [
                'title' => 'Running Late',
                'icon' => 'clock-hour-4',
                'channel_hint' => 'Ops',
                'preview' => 'Hi {{name}}, running around {{delay}} minutes late. Sorry for the delay.',
                'defaults' => ['delay' => '15'],
                'command' => 'service.update',
            ],
            'on_my_way' => [
                'title' => 'On My Way',
                'icon' => 'road',
                'channel_hint' => 'Field',
                'preview' => 'Hi {{name}}, I am on my way now. ETA {{eta}}.',
                'defaults' => ['eta' => '20 mins'],
                'command' => 'service.update',
            ],
            'confirm_job' => [
                'title' => 'Confirm Job',
                'icon' => 'circle-check',
                'channel_hint' => 'Booking',
                'preview' => 'Hi {{name}}, just confirming your job for {{job_time}}. Reply if anything has changed.',
                'defaults' => ['job_time' => 'tomorrow 10:00'],
                'command' => 'booking.confirm',
            ],
            'reschedule' => [
                'title' => 'Reschedule',
                'icon' => 'calendar-time',
                'channel_hint' => 'Booking',
                'preview' => 'Hi {{name}}, we need to reschedule your booking. What time suits you best?',
                'defaults' => [],
                'command' => 'booking.reschedule',
            ],
            'thank_you' => [
                'title' => 'Thank You',
                'icon' => 'heart-handshake',
                'channel_hint' => 'Care',
                'preview' => 'Thanks {{name}} — we appreciate your business. Let us know if you need anything else.',
                'defaults' => [],
                'command' => 'conversation.thank_you',
            ],
            'review_request' => [
                'title' => 'Review Request',
                'icon' => 'star',
                'channel_hint' => 'Growth',
                'preview' => 'Hi {{name}}, if you were happy with the service we would love a quick review. {{review_link}}',
                'defaults' => ['review_link' => 'https://example.com/review'],
                'command' => 'review.request',
            ],
        ];
    }

    /** @return array<string,mixed>|null */
    public function preset(string $key): ?array
    {
        return $this->presets()[$key] ?? null;
    }

    /** @param array<string,mixed> $context */
    public function renderPreset(string $key, array $context = []): string
    {
        $preset = $this->preset($key);
        if (! $preset) {
            return '';
        }

        $variables = array_merge($preset['defaults'] ?? [], $context);
        $message = (string) ($preset['preview'] ?? '');
        preg_match_all('/{{\s*([a-zA-Z0-9_]+)\s*}}/', $message, $matches);
        foreach ($matches[1] ?? [] as $name) {
            $message = str_replace('{{' . $name . '}}', (string) Arr::get($variables, $name, ''), $message);
            $message = str_replace('{{ ' . $name . ' }}', (string) Arr::get($variables, $name, ''), $message);
        }

        return preg_replace('/\s+/', ' ', trim($message)) ?: '';
    }

    /** @param array<string,mixed> $context @return array<string,mixed> */
    public function sendPreset(MarketingConversation $conversation, string $key, array $context = []): array
    {
        $preset = $this->preset($key);
        if (! $preset) {
            return ['status' => false, 'message' => 'Preset not found.'];
        }

        $message = $this->renderPreset($key, $context);
        if ($message === '') {
            return ['status' => false, 'message' => 'Preset rendered an empty message.'];
        }

        $channel = (string) $conversation->type;
        $sendResult = ['status' => false, 'message' => 'Unsupported channel'];

        if ($channel === 'whatsapp') {
            $this->whatsappSenderService->setWhatsappChannel((int) $conversation->user_id);
            $sendResult = $this->whatsappSenderService->sendText((string) $conversation->session_id, $message);
        } elseif ($channel === 'telegram') {
            try {
                $this->telegramSenderService->setBot((int) $conversation->user_id)->sendText($message, $this->resolveTelegramReceiver($conversation));
                $sendResult = ['status' => true, 'message' => 'Message sent'];
            } catch (\Throwable $e) {
                $sendResult = ['status' => false, 'message' => $e->getMessage()];
            }
        }

        MarketingMessageHistory::query()->create([
            'conversation_id' => $conversation->getKey(),
            'message_id' => random_int(100000000, 999999999),
            'model' => null,
            'role' => 'assistant',
            'message' => $message,
            'type' => 'preset:' . $key,
            'message_type' => 'text',
            'content_type' => 'text',
            'read_at' => now(),
            'created_at' => now(),
        ]);

        $payload = $conversation->customer_payload ?? [];
        $payload['last_quick_action'] = [
            'key' => $key,
            'title' => $preset['title'] ?? Str::headline($key),
            'sent_at' => now()->toIso8601String(),
            'status' => (bool) ($sendResult['status'] ?? false),
        ];
        $conversation->update([
            'last_activity_at' => now(),
            'customer_payload' => $payload,
        ]);

        $signal = $this->signalBridge->emitFromCommand($conversation, [
            'command' => $preset['command'] ?? 'conversation.quick_message',
            'params' => [
                'preset' => $key,
                'message' => $message,
            ],
            'is_actionable' => true,
            'requires_confirmation' => false,
        ], 'quick_action');

        return [
            'status' => (bool) ($sendResult['status'] ?? false),
            'message' => $sendResult['message'] ?? 'Processed',
            'content' => $message,
            'signal' => $signal,
            'preset' => [
                'key' => $key,
                'title' => $preset['title'] ?? Str::headline($key),
            ],
        ];
    }

    protected function resolveTelegramReceiver(MarketingConversation $conversation): ?string
    {
        $payload = $conversation->customer_payload ?? [];
        $candidate = Arr::get($payload, 'contact_id');
        if ($candidate) {
            return (string) $candidate;
        }

        $session = (string) ($conversation->session_id ?? '');
        if (str_contains($session, '-')) {
            return explode('-', $session)[0] ?: null;
        }

        return $session !== '' ? $session : null;
    }
}
