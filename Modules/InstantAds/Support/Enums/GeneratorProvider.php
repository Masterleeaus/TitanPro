<?php

namespace Modules\InstantAds\Support\Enums;

enum GeneratorProvider: string
{
    case DALLE = 'dalle';
    case STABLE_DIFFUSION = 'sd';
    case FLUX = 'flux';
    case REPLICATE = 'replicate';

    public static function fromModel(?string $model): self
    {
        $model = strtolower((string) $model);

        return match (true) {
            str_contains($model, 'dall') => self::DALLE,
            str_contains($model, 'stable') => self::STABLE_DIFFUSION,
            str_contains($model, 'flux') => self::FLUX,
            default => self::REPLICATE,
        };
    }
}
