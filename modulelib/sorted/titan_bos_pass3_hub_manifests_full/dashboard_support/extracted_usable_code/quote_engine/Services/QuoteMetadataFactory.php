<?php

namespace App\Extensions\ProductPhotography\Services;

class QuoteMetadataFactory
{
    public function make(array $payload): array
    {
        $service = $payload['service_type'] ?? 'Service';
        $mode = $payload['visual_mode'] ?? 'quote_preview';
        $tier = $payload['package_tier'] ?? 'standard';

        return [
            'result_title' => "{$service} - " . ucwords(str_replace('_', ' ', $mode)),
            'result_summary' => "Visual prepared for {$service} using the {$tier} package framing.",
            'usage_tag' => 'quote_asset',
        ];
    }
}
