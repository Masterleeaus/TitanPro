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

    public function events(): Collection
    {
        return RewindEvent::query()->latest('id')->limit(50)->get();
    }
}
