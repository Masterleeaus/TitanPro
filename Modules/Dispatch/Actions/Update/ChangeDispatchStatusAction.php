<?php

declare(strict_types=1);

namespace Modules\Dispatch\Actions\Update;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;
use Modules\Dispatch\Models\AssignShift;
use Modules\Dispatch\Models\DispatchStatusLog;

class ChangeDispatchStatusAction
{
    public function execute(AssignShift $assignment, string $toStatus, ?string $notes = null): AssignShift
    {
        $fromStatus = $assignment->dispatch_status ?? Config::get('dispatch.workflows.default_status', 'scheduled');
        $allowed = Config::get("dispatch.workflows.transitions.{$fromStatus}", []);

        if ($allowed !== [] && ! in_array($toStatus, $allowed, true)) {
            throw new InvalidArgumentException("Invalid dispatch transition from {$fromStatus} to {$toStatus}.");
        }

        $assignment->forceFill(['dispatch_status' => $toStatus, 'dispatch_notes' => $notes])->save();

        DispatchStatusLog::query()->create([
            'company_id' => $assignment->company_id,
            'assign_shift_id' => $assignment->id,
            'work_order_id' => $assignment->work_order_id,
            'appointment_id' => $assignment->appointment_id,
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'changed_by' => Auth::id(),
            'changed_at' => now(),
            'notes' => $notes,
        ]);

        return $assignment->refresh();
    }
}
