<?php

namespace App\Extensions\ProductPhotography\Services;

class RenderPayloadFactory
{
    public function make(array $payload, string $prompt): array
    {
        return [
            'engine' => 'productphotography_quotemaker_mvp',
            'prompt' => $prompt,
            'visual_mode' => $payload['visual_mode'] ?? 'quote_preview',
            'service_type' => $payload['service_type'] ?? null,
            'site_type' => $payload['site_type'] ?? null,
            'package_tier' => $payload['package_tier'] ?? 'standard',
            'reference' => $payload['quote_reference'] ?? null,
        ];
    }
}
