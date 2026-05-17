<?php

declare(strict_types=1);

namespace Modules\Dispatch\Services\Core;

use Illuminate\Validation\ValidationException;
use Modules\Dispatch\Actions\Update\ChangeDispatchStatusAction;
use Modules\Dispatch\Contracts\Services\DispatchStatusContract;
use Modules\Dispatch\Contracts\Services\DispatchTransitionGuardContract;
use Modules\Dispatch\Events\Domain\DispatchStatusChanged;
use Modules\Dispatch\Models\AssignShift;

class DispatchStatusService implements DispatchStatusContract
{
    public function __construct(
        private readonly ChangeDispatchStatusAction $action,
        private readonly DispatchTransitionGuardContract $guard,
    ) {}

    public function change(AssignShift $assignment, string $status, ?string $notes = null, ?int $changedBy = null): AssignShift
    {
        $from = $assignment->dispatch_status ?: 'scheduled';

        if (! $this->guard->allows($assignment, $status)) {
            throw ValidationException::withMessages([
                'status' => sprintf('Cannot transition dispatch assignment from %s to %s.', $from, $status),
            ]);
        }

        $updated = $this->action->execute($assignment, $status, $notes, $changedBy);

        event(new DispatchStatusChanged($updated, $from, $status, $notes));

        return $updated;
    }
}
