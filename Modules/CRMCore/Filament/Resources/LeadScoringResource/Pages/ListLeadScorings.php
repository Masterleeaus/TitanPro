<?php

namespace Modules\CRMCore\Filament\Resources\LeadScoringResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\CRMCore\Filament\Resources\LeadScoringResource;

class ListLeadScorings extends ListRecords
{
    protected static string $resource = LeadScoringResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Add Lead'),
        ];
    }
}
