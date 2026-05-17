<?php

declare(strict_types=1);

namespace Modules\Budgeting\Filament\Resources\ReimbursementBatchResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Budgeting\Filament\Resources\ReimbursementBatchResource;

class CreateReimbursementBatch extends CreateRecord
{
    protected static string $resource = ReimbursementBatchResource::class;
}
