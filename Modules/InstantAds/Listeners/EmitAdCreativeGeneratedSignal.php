<?php

namespace Modules\InstantAds\Listeners;

use Modules\InstantAds\Events\AdCreativeGenerated;

class EmitAdCreativeGeneratedSignal
{
    public function handle(AdCreativeGenerated $event): void
    {
        event('InstantAds::AdCreativeGenerated', [
            'creative_id' => $event->creative->getKey(),
            'company_id' => $event->companyId,
            'url' => $event->url,
            'provider' => $event->provider,
            'prompt' => $event->prompt,
        ]);
    }
}
