<?php

declare(strict_types=1);

namespace Modules\Dispatch\Services\Core;

use Modules\Dispatch\Contracts\Services\DispatchTransitionGuardContract;
use Modules\Dispatch\Models\AssignShift;

class DispatchTransitionGuard implements DispatchTransitionGuardContract
{
    /** @var array<string,array<int,string>> */
    private array $map = [
        'scheduled' => ['accepted', 'cancelled'],
        'pending' => ['accepted', 'cancelled'],
        'accepted' => ['en_route', 'cancelled'],
        'en_route' => ['arrived', 'cancelled'],
        'arrived' => ['in_progress', 'cancelled'],
        'in_progress' => ['completed', 'cancelled'],
        'completed' => [],
        'cancelled' => [],
    ];

    public function allows(AssignShift $assignment, string $toStatus): bool
    {
        $fromStatus = $assignment->dispatch_status ?: 'scheduled';

        return in_array($toStatus, $this->allowedNextStatuses($fromStatus), true) || $toStatus === $fromStatus;
    }

    /** @return array<int,string> */
    public function allowedNextStatuses(?string $fromStatus): array
    {
        return $this->map[$fromStatus ?: 'scheduled'] ?? config('dispatch.statuses.assignment', []);
    }
}
