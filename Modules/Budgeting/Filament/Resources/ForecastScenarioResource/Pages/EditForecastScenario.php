<?php

declare(strict_types=1);

namespace Modules\Budgeting\Filament\Resources\ForecastScenarioResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\Budgeting\Filament\Resources\ForecastScenarioResource;

class EditForecastScenario extends EditRecord
{
    protected static string $resource = ForecastScenarioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
