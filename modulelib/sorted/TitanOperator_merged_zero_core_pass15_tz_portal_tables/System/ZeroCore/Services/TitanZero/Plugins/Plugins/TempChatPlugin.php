<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Plugins\Plugins;

use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Plugins\Contracts\AbstractPlugin;
use Illuminate\Routing\Router;

/**
 * TempChatPlugin
 * Toggle-gated temporary/anonymous chat sessions.
 * No routes — triggered via startNewChat() with 'chatpro-temp' type.
 */
class TempChatPlugin extends AbstractPlugin
{
    public function id(): string    { return 'tzc-temp-chat'; }
    public function label(): string { return 'Temporary Chat'; }

    public function enabled(): bool
    {
        return (int) \Helper::setting('chatpro-temp-chat-allowed', 1) === 1;
    }

    public function settings(): array
    {
        return ['chatpro-temp-chat-allowed' => 1];
    }
}
