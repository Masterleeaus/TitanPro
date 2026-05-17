<?php

declare(strict_types=1);

namespace Modules\Budgeting\Filament\Resources\BudgetActualResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Budgeting\Filament\Resources\BudgetActualResource;

class CreateBudgetActual extends CreateRecord
{
    protected static string $resource = BudgetActualResource::class;
}
