<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Support;

class TitanTalkRealtimeConfig
{
    public static function enabled(): bool
    {
        return (bool) config('titantalk-realtime.enabled', false);
    }

    public static function driver(): string
    {
        return (string) config('titantalk-realtime.driver', 'ably');
    }

    public static function ablyPrivateKey(): string
    {
        return (string) config('titantalk-realtime.ably_private_key', '');
    }

    public static function ablyPublicKey(): string
    {
        return (string) config('titantalk-realtime.ably_public_key', '');
    }

    public static function channelPrefix(): string
    {
        return (string) config('titantalk-realtime.channel_prefix', 'titantalk-');
    }
}
