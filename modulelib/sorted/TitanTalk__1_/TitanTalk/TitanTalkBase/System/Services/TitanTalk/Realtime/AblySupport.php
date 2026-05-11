<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Services\TitanTalk\Realtime;

use Ably\AblyRest;
use App\Extensions\MarketingBot\System\Support\TitanTalkRealtimeConfig;

abstract class AblySupport
{
    protected static function apiKey(): ?string
    {
        $key = TitanTalkRealtimeConfig::ablyPrivateKey();

        return $key !== '' ? $key : null;
    }

    protected static function enabled(): bool
    {
        return TitanTalkRealtimeConfig::enabled() && static::apiKey() !== null;
    }

    protected static function ably(): ?AblyRest
    {
        if (! static::enabled() || ! class_exists(AblyRest::class)) {
            return null;
        }

        return new AblyRest(static::apiKey());
    }

    protected static function channelName(string $suffix): string
    {
        return TitanTalkRealtimeConfig::channelPrefix() . $suffix;
    }
}
