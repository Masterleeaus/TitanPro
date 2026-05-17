<?php

declare(strict_types=1);

namespace Modules\Dispatch\Contracts\Services;

use Modules\Dispatch\Models\AssignShift;

interface DispatchTransitionGuardContract
{
    public function allows(AssignShift $assignment, string $toStatus): bool;

    /** @return array<int,string> */
    public function allowedNextStatuses(?string $fromStatus): array;
}
