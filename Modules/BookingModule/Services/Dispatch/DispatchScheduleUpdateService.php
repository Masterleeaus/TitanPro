<?php

namespace Modules\BookingModule\Services\Dispatch;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\BookingModule\Entities\Schedule;
use Modules\BookingModule\Entities\ScheduleAssignment;
use Modules\BookingModule\Events\ScheduleAssigned;
use Modules\BookingModule\Events\ScheduleRescheduled;
use Modules\BookingModule\Jobs\SendBookingReminderJob;
use Modules\BookingModule\Services\ScheduleAssignmentService;
use Modules\BookingModule\Services\ScheduleCapacityService;
use Modules\BookingModule\Services\ScheduleConflictService;

class DispatchScheduleUpdateService
{
    public function __construct(
        protected ScheduleCapacityService   $capacity,
        protected ScheduleConflictService   $conflicts,
        protected ScheduleAssignmentService $assignmentService,
    ) {}

    /**
     * Update schedule date/time and (optionally) assigned user.
     * Enforces capacity and conflict rules when assignment changes or times change.
     */
    public function update(int $scheduleId, array $payload): array
    {
        $schedule = Schedule::find($scheduleId);
        if (!$schedule) {
            return ['ok' => false, 'message' => 'Schedule not found'];
        }
        if (!$this->isWithinTenantBoundary($schedule)) {
            return ['ok' => false, 'message' => 'Forbidden', 'status' => 403];
        }

        $oldWindow = [
            'date' => $schedule->date,
            'start_time' => $schedule->start_time,
            'end_time' => $schedule->end_time,
        ];
        $fromUserId = $schedule->assigned_to;
        $toUserId   = isset($payload['user_id']) && $payload['user_id'] ? (int)$payload['user_id'] : null;
        $isReassign = $fromUserId && $toUserId && $fromUserId !== $toUserId;

        $schedule->date = $payload['date'];
        $schedule->start_time = $payload['start_time'];
        $schedule->end_time = $payload['end_time'];

        if (array_key_exists('notes', $payload)) {
            $schedule->notes = $payload['notes'];
        }

        // Apply assignment (optional)
        if ($toUserId === null) {
            $schedule->assigned_to = null;
            $schedule->user_id = null;
            $schedule->assignment_status = 'unassigned';
            $schedule->assigned_at = null;
            $schedule->assigned_by = null;
        } else {
            // Capacity check
            [$ok, $msg] = $this->capacity->canAssignUserToSchedule($schedule, $toUserId);
            if (!$ok) {
                return ['ok' => false, 'message' => $msg ?? 'Capacity limit'];
            }

            $effective = $this->capacity->getEffectiveCapacity((int)$schedule->created_by, (int)$schedule->workspace, $toUserId);
            if (!empty($effective['enforce_conflicts'])) {
                if ($this->conflicts->hasConflict($schedule, $toUserId, (bool)($effective['count_pending_too'] ?? false))) {
                    return ['ok' => false, 'message' => __('bookingmodule::capacity.errors.conflict')];
                }
            }

            $schedule->assigned_to = $toUserId;
            $schedule->user_id = $toUserId; // compatibility
            $schedule->assignment_status = 'assigned';
            $schedule->assigned_at = Carbon::now();
            $schedule->assigned_by = Auth::id();
        }

        $schedule->save();

        $newWindow = [
            'date' => $schedule->date,
            'start_time' => $schedule->start_time,
            'end_time' => $schedule->end_time,
        ];

        // Audit history
        $history = new ScheduleAssignment();
        $history->schedule_id = $schedule->id;
        $history->assigned_to = $schedule->assigned_to;
        $history->assigned_by = Auth::id();
        $history->action = 'dispatch_update';
        $history->notes = 'Dispatch quick edit update';
        $history->save();

        if ($oldWindow !== $newWindow) {
            event(new ScheduleRescheduled(
                $schedule,
                $oldWindow,
                $newWindow,
                (int) ($schedule->company_id ?? 0) ?: null,
                Auth::id() ?: null,
                ['source' => 'dispatch_quick_edit'],
            ));
        }

        if ((int) ($fromUserId ?? 0) !== (int) ($toUserId ?? 0)) {
            event(new ScheduleAssigned(
                $schedule,
                $fromUserId ? (int) $fromUserId : null,
                $toUserId ? (int) $toUserId : null,
                (int) ($schedule->company_id ?? 0) ?: null,
                Auth::id() ?: null,
                ['source' => 'dispatch_quick_edit'],
            ));
        }

        // Dispatch assignment-trigger reminders when user changes.
        if ($toUserId) {
            $trigger = $isReassign
                ? SendBookingReminderJob::TRIGGER_RESCHEDULE
                : SendBookingReminderJob::TRIGGER_ASSIGN;
            $this->assignmentService->dispatchRemindersForSchedule($schedule, $trigger);
        }

        return ['ok' => true, 'message' => 'Updated', 'schedule_id' => $schedule->id];
    }

    private function isWithinTenantBoundary(Schedule $schedule): bool
    {
        $companyId = null;
        if (function_exists('company') && company()) {
            $companyId = (int) company()->id;
        } elseif (Auth::check()) {
            $companyId = (int) (Auth::user()->company_id ?? Auth::user()->organization_id ?? 0);
        }

        if ($companyId && (int) ($schedule->company_id ?? 0) !== $companyId) {
            return false;
        }

        if (function_exists('getActiveWorkSpace')) {
            $workspaceId = (int) getActiveWorkSpace();
            if ($workspaceId > 0 && (int) ($schedule->workspace ?? 0) !== $workspaceId) {
                return false;
            }
        }

        if (function_exists('creatorId')) {
            $creatorId = (int) creatorId();
            if ($creatorId > 0 && (int) ($schedule->created_by ?? 0) !== $creatorId) {
                return false;
            }
        }

        return true;
    }
}
