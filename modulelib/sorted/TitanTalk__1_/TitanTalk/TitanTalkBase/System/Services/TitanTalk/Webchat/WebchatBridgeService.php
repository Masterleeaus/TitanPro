<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Services\TitanTalk\Webchat;

use App\Extensions\MarketingBot\System\Models\TitanTalk\Conversation;
use App\Extensions\MarketingBot\System\Services\Conversation\AiChatbotService;
use App\Extensions\MarketingBot\System\Services\Conversation\IntentClassifierService;
use App\Extensions\MarketingBot\System\Services\TitanTalk\RolePackResolverService;

class WebchatBridgeService
{
    public function __construct(
        protected AiChatbotService $chatbot,
        protected IntentClassifierService $intentClassifier,
        protected RolePackResolverService $roleResolver,
    ) {}

    /**
     * TitanTalk webchat lane: classify → resolve role pack → generate reply payload.
     *
     * @param array<string,mixed> $context
     * @return array<string,mixed>
     */
    public function handle(Conversation $conversation, string $message, array $context = []): array
    {
        $classification = $this->intentClassifier->classify($message, array_merge($context, ['channel' => 'webchat']));
        $intent = $classification['intent'];
        $rolePack = $this->roleResolver->resolve($intent);
        $reply = $this->chatbot->reply($conversation, $message);

        return [
            'channel' => 'webchat',
            'intent' => $intent->value,
            'role_pack' => $rolePack,
            'classification' => [
                'confidence' => $classification['confidence'] ?? null,
                'matched_rule' => $classification['matched_rule'] ?? null,
            ],
            'reply' => $reply,
        ];
    }
}
