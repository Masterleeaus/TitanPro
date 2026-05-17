<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Services\TitanTalk\Tools;

use App\Extensions\MarketingBot\System\Enums\ConversationIntent;
use App\Extensions\MarketingBot\System\Models\MarketingConversation;

class ToolCallEngine
{
    public function __construct(
        protected ToolRegistry $toolRegistry,
        protected PredixToolBridge $predixToolBridge,
    ) {}

    /**
     * @param array<string,mixed> $classification
     * @return array<int,array<string,mixed>>
     */
    public function planForConversation(MarketingConversation $conversation, array $classification): array
    {
        $intent = $classification['intent'];
        if (! $intent instanceof ConversationIntent) {
            return [];
        }

        $entities = (array) ($classification['entities'] ?? []);
        $tools = $this->toolRegistry->forIntent($intent);
        $plans = [];

        foreach ($tools as $tool) {
            $plans[] = $this->predixToolBridge->plan($conversation, $tool, $this->mapArguments($tool, $entities, $conversation));
        }

        return $plans;
    }

    /**
     * @param array<string,mixed> $tool
     * @param array<string,mixed> $entities
     * @return array<string,mixed>
     */
    protected function mapArguments(array $tool, array $entities, MarketingConversation $conversation): array
    {
        $arguments = [
            'conversation_id' => $conversation->getKey(),
            'channel' => $conversation->type,
        ];

        foreach ((array) ($tool['parameters'] ?? []) as $name => $type) {
            if (array_key_exists($name, $entities)) {
                $arguments[$name] = $entities[$name];
            }
        }

        return $arguments;
    }
}
