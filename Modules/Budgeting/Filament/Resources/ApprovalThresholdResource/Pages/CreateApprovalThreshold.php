<?php

declare(strict_types=1);

namespace Modules\Budgeting\Filament\Resources\ApprovalThresholdResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Budgeting\Filament\Resources\ApprovalThresholdResource;

class CreateApprovalThreshold extends CreateRecord
{
    protected static string $resource = ApprovalThresholdResource::class;
}
