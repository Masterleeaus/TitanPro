<?php

declare(strict_types=1);

namespace Modules\Budgeting\Filament\Resources\VarianceResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Budgeting\Filament\Resources\VarianceResource;

class ListVariances extends ListRecords
{
    protected static string $resource = VarianceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
