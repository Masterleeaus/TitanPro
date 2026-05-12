<?php

namespace App\Filament\TitanNexus\Resources\LeadsSegmentResource\Pages;

use App\Filament\TitanNexus\Resources\LeadsSegmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLeadsSegment extends EditRecord
{
    protected static string $resource = LeadsSegmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
