<?php

declare(strict_types=1);

namespace Modules\Budgeting\Filament\Resources\ExpenseResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Budgeting\Filament\Resources\ExpenseResource;

class CreateExpense extends CreateRecord
{
    protected static string $resource = ExpenseResource::class;
}
