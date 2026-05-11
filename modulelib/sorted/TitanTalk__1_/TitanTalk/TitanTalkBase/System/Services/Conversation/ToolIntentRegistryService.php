<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Services\Conversation;

use App\Extensions\MarketingBot\System\Enums\ConversationIntent;
use App\Extensions\MarketingBot\System\Services\TitanTalk\Tools\ToolRegistry;

class ToolIntentRegistryService
{
    public function __construct(
        protected ToolRegistry $toolRegistry,
    ) {}

    /**
     * @return list<string>
     */
    public function toolsForIntent(string $intent): array
    {
        $enum = ConversationIntent::tryFrom($intent) ?? ConversationIntent::GENERAL;

        return array_values(array_map(static fn (array $tool): string => (string) $tool['name'], $this->toolRegistry->forIntent($enum)));
    }

    /**
     * @return array<string,mixed>|null
     */
    public function schemaForTool(string $toolName): ?array
    {
        return $this->toolRegistry->all()[$toolName] ?? null;
    }
}
