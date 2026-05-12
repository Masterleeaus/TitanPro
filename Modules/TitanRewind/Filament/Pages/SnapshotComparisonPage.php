<?php

namespace Modules\TitanRewind\Filament\Pages;

use Filament\Pages\Page;
use Modules\TitanRewind\Models\RewindEvent;

class SnapshotComparisonPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrows-right-left';

    protected static string|\UnitEnum|null $navigationGroup = 'Titan Rewind';

    protected static ?string $navigationLabel = 'Snapshot Comparison';

    protected static ?int $navigationSort = 13;

    public function compareLatest(int $caseId): array
    {
        $events = RewindEvent::query()
            ->where('case_id', $caseId)
            ->latest('id')
            ->limit(2)
            ->get();

        return [
            'current' => $events->first()?->payload_json ?? [],
            'previous' => $events->skip(1)->first()?->payload_json ?? [],
        ];
    }
}
