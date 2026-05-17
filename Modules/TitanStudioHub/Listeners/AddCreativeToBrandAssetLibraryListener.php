<?php

namespace Modules\TitanStudioHub\Listeners;

use Modules\TitanStudioHub\Support\BrandAssetLibraryStore;

class AddCreativeToBrandAssetLibraryListener
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public function handle(array $payload): void
    {
        BrandAssetLibraryStore::add(
            isset($payload['company_id']) ? (int) $payload['company_id'] : null,
            [
                'creative_id' => $payload['creative_id'] ?? null,
                'url' => $payload['url'] ?? null,
                'provider' => $payload['provider'] ?? null,
                'prompt' => $payload['prompt'] ?? null,
            ]
        );
    }
}
