<?php

declare(strict_types=1);

namespace Modules\Budgeting\Filament\Resources\ExpenseResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Budgeting\Filament\Resources\ExpenseResource;

class ListExpenses extends ListRecords
{
    protected static string $resource = ExpenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
