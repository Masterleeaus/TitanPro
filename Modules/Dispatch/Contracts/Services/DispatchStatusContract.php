<?php

declare(strict_types=1);

namespace Modules\Dispatch\Contracts\Services;

use Modules\Dispatch\Models\AssignShift;

interface DispatchStatusContract
{
    public function change(AssignShift $assignment, string $status, ?string $notes = null, ?int $changedBy = null): AssignShift;
}
