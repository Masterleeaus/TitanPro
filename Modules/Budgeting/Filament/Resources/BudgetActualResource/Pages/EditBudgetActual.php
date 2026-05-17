<?php

declare(strict_types=1);

namespace Modules\Budgeting\Filament\Resources\BudgetActualResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\Budgeting\Filament\Resources\BudgetActualResource;

class EditBudgetActual extends EditRecord
{
    protected static string $resource = BudgetActualResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
