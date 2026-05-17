<?php

declare(strict_types=1);

namespace Modules\Budgeting\Filament\Resources\BudgetActualResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Budgeting\Filament\Resources\BudgetActualResource;

class ListBudgetActuals extends ListRecords
{
    protected static string $resource = BudgetActualResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
