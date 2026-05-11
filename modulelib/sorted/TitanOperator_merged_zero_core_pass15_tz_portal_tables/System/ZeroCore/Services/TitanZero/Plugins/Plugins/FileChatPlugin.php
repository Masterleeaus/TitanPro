<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Plugins\Plugins;

use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Plugins\Contracts\AbstractPlugin;
use Illuminate\Routing\Router;

/**
 * FileChatPlugin
 *
 * PDF and file-based chat via OpenAI vector stores.
 * Gated by 'chatpro_file_chat_allowed' setting.
 * No dedicated routes — AIFileChatService is invoked inline from CoreChat stream.
 */
class FileChatPlugin extends AbstractPlugin
{
    public function id(): string    { return 'tzc-file-chat'; }
    public function label(): string { return 'File Chat (PDF / Vector)'; }

    public function enabled(): bool
    {
        return (int) \Helper::setting('chatpro_file_chat_allowed', 1) === 1;
    }

    public function settings(): array
    {
        return ['chatpro_file_chat_allowed' => 1];
    }
}
