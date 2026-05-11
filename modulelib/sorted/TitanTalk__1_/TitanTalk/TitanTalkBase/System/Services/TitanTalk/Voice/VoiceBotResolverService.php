<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Services\TitanTalk\Voice;

class VoiceBotResolverService
{
    public function providerConfig(?string $provider = null): array
    {
        $provider = $provider ?: (string) config('titantalk-voice.default_provider', 'internal');

        return match ($provider) {
            'elevenlabs' => [
                'provider' => 'elevenlabs',
                'enabled' => (bool) config('titantalk-voice.providers.elevenlabs.enabled', false),
                'websocket' => (bool) config('titantalk-voice.providers.elevenlabs.websocket', false),
                'model' => config('titantalk-voice.providers.elevenlabs.model'),
            ],
            default => [
                'provider' => 'internal',
                'enabled' => true,
                'websocket' => false,
                'model' => config('titantalk-voice.providers.internal.model', 'default'),
            ],
        };
    }

    public function supportsRealtime(?string $provider = null): bool
    {
        return (bool) ($this->providerConfig($provider)['websocket'] ?? false);
    }
}
