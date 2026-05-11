<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Voice;

use App\Extensions\MarketingBot\System\Commands\CommandInterpreter;
use App\Extensions\MarketingBot\System\Models\MarketingConversation;
use App\Extensions\MarketingBot\System\Signals\TitanTalkSignalBridge;
use App\Extensions\MarketingBot\System\Services\Conversation\AiChatbotService;

class VoiceCommandRouter
{
    public function __construct(
        protected AiChatbotService $aiChatbotService,
        protected CommandInterpreter $commandInterpreter,
        protected TitanTalkSignalBridge $signalBridge,
    ) {}

    /**
     * @return array<string,mixed>
     */
    public function handle(MarketingConversation $conversation, string $transcript): array
    {
        $cleanTranscript = $this->stripWakePhrase($transcript);
        $response = $this->aiChatbotService->replyWithContext($conversation, $cleanTranscript);
        $command = $this->commandInterpreter->interpret($cleanTranscript, $response['classification'] ?? []);
        $signal = $this->signalBridge->emitFromCommand($conversation->fresh() ?? $conversation, $command, 'voice');

        return $response + [
            'voice_transcript' => $cleanTranscript,
            'command' => $command,
            'signal' => $signal,
        ];
    }

    private function stripWakePhrase(string $transcript): string
    {
        $wakeNames = (array) config('titantalk.voice_wake_names', ['zero']);
        $clean = trim($transcript);

        foreach ($wakeNames as $wakeName) {
            $pattern = '/^' . preg_quote((string) $wakeName, '/') . '[,\s:;-]+/i';
            $clean = preg_replace($pattern, '', $clean) ?? $clean;
        }

        return trim($clean);
    }
}
