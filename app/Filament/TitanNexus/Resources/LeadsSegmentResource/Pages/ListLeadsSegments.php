<?php

namespace App\Filament\TitanNexus\Resources\LeadsSegmentResource\Pages;

use App\Filament\TitanNexus\Resources\LeadsSegmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLeadsSegments extends ListRecords
{
    protected static string $resource = LeadsSegmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
