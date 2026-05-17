<?php

namespace Modules\InstantAds\Models;

use Modules\InstantAds\Support\Scopes\ScopedByCompany;

class AdCreative extends AiImagePro
{
    protected static function booted(): void
    {
        static::addGlobalScope(new ScopedByCompany);
    }
}
