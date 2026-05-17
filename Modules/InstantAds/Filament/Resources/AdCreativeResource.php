<?php

namespace Modules\InstantAds\Filament\Resources;

use Filament\Resources\Resource;
use Modules\InstantAds\Actions\GenerateAdImageAction;
use Modules\InstantAds\Models\AdCreative;

class AdCreativeResource extends Resource
{
    protected static ?string $model = AdCreative::class;
    protected static ?string $navigationIcon = 'heroicon-o-photo';

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('use_instant_ads') ?? false;
    }

    /**
     * @param  array<string, mixed>  $params
     */
    public static function generateFromPanel(array $params, ?int $userId = null, mixed $driver = null): int
    {
        return app(GenerateAdImageAction::class)->dispatch($params, $userId, $driver);
    }
}
