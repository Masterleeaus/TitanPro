<?php

declare(strict_types=1);

namespace Modules\Budgeting\Filament\Resources\ForecastScenarioResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Budgeting\Filament\Resources\ForecastScenarioResource;

class CreateForecastScenario extends CreateRecord
{
    protected static string $resource = ForecastScenarioResource::class;
}
