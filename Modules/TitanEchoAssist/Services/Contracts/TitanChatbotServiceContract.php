<?php

namespace Modules\TitanChatbot\Services\Contracts;

interface TitanChatbotServiceContract
{
    public function moduleKey(): string;

    public function enabled(): bool;

    /** @return array<int, string> */
    public function capabilities(): array;

    /** @return array<string, mixed> */
    public function healthSummary(): array;
}
