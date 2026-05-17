<?php

namespace App\Filament\Admin\Resources\PlatformPlanResource\Pages;

use App\Filament\Admin\Resources\PlatformPlanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPlatformPlans extends ListRecords
{
    protected static string $resource = PlatformPlanResource::class;
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
