<?php

namespace Modules\InstantAds\Events;

use Modules\InstantAds\Models\AiImagePro;

class AdCreativeGenerated
{
    public function __construct(
        public readonly AiImagePro $creative,
        public readonly string $url,
        public readonly string $provider,
        public readonly string $prompt,
        public readonly ?int $companyId,
    ) {}
}
