<?php

declare(strict_types=1);

namespace Modules\Budgeting\Filament\Resources\VarianceResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Budgeting\Filament\Resources\VarianceResource;

class CreateVariance extends CreateRecord
{
    protected static string $resource = VarianceResource::class;
}
