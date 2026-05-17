<?php

namespace Modules\ExampleModule\UI\ZeroChat\ChannelFallbacks;

class ChannelFallbackRenderer
{
    public function forChannel(array $richResponse, string $channel): string
    {
        return $richResponse['fallback'] ?? 'Zero has an update. Open the PWA for the full interactive view.';
    }
}
