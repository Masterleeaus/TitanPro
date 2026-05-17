<?php

namespace Modules\TitanRewind\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Modules\TitanRewind\Models\RewindEvent;

class AuditTrailPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clock';

    protected static string|\UnitEnum|null $navigationGroup = 'Titan Rewind';

    protected static ?string $navigationLabel = 'Audit Trail';

    protected static ?int $navigationSort = 11;

    public function events(int $limit = 50): Collection
    {
        $safeLimit = max(1, min(100, $limit));

        return RewindEvent::query()->latest('id')->limit($safeLimit)->get();
    }
}
