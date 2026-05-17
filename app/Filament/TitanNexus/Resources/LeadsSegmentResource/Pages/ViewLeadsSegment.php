<?php

namespace App\Filament\TitanNexus\Resources\LeadsSegmentResource\Pages;

use App\Filament\TitanNexus\Resources\LeadsSegmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewLeadsSegment extends ViewRecord
{
    protected static string $resource = LeadsSegmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
