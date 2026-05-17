<?php

declare(strict_types=1);

namespace Modules\Budgeting\Filament\Resources\ForecastScenarioResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Budgeting\Filament\Resources\ForecastScenarioResource;

class ListForecastScenarios extends ListRecords
{
    protected static string $resource = ForecastScenarioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
