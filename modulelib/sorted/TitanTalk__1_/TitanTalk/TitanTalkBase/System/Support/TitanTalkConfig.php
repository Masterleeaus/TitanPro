<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Support;

class TitanTalkConfig
{
    public static function get(?string $key = null, mixed $default = null): mixed
    {
        $value = config($key === null ? 'titantalk' : 'titantalk.' . $key);
        if ($value !== null) {
            return $value;
        }

        return config($key === null ? 'marketing-bot' : 'marketing-bot.' . $key, $default);
    }

    public static function assistantEnabled(): bool
    {
        return (bool) self::get('assistant_enabled', true);
    }

    public static function defaultRolePack(): string
    {
        return (string) self::get('default_role_pack', 'titantalk.reception');
    }

    public static function maxReplyChars(): int
    {
        return (int) self::get('max_reply_chars', 1500);
    }


    public static function copilotSummaryWindow(): int
    {
        return (int) self::get('copilot_summary_window', 12);
    }

    public static function copilotEnabled(): bool
    {
        return (bool) self::get('copilot_enabled', true);
    }

    public static function contextHistoryWindow(): int
    {
        return (int) self::get('context_history_window', 10);
    }

    public static function contextIncludeSignals(): bool
    {
        return (bool) self::get('context_include_signals', true);
    }
}


