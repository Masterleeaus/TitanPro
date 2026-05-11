<?php

namespace Modules\TitanEchoAssist\Actions;

use Modules\TitanEchoAssist\Services\GeneratorBridge;

class GenerateChatbotReply
{
    public function __construct(private readonly ?GeneratorBridge $generator = null) {}

    /** @param array<string, mixed> $payload */
    public function handle(array $payload): array
    {
        $message = trim((string) ($payload['message'] ?? ''));
        $conversationId = $payload['conversation_id'] ?? null;

        if ($message === '') {
            return ['ok' => false, 'error' => 'message_required'];
        }

        if ($this->generator && method_exists($this->generator, 'reply')) {
            return ['ok' => true, 'reply' => $this->generator->reply($message, $payload), 'conversation_id' => $conversationId];
        }

        return [
            'ok' => true,
            'reply' => $message,
            'conversation_id' => $conversationId,
            'source' => 'safe-local-echo',
        ];
    }
}
