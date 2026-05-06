<?php

namespace Modules\CleaningJobs\Policies\PlanningCore;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\CleaningJobs\Models\PlanningCore\Timesheet;

class TimesheetPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any timesheets.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('planning.view-timesheets') || $user->can('planning.view-own-timesheets');
    }

    /**
     * Determine whether the user can view the timesheet.
     */
    public function view(User $user, Timesheet $timesheet): bool
    {
        if ($user->can('planning.view-timesheets')) {
            return true;
        }

        if ($user->can('planning.view-own-timesheets')) {
            return $timesheet->user_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create timesheets.
     */
    public function create(User $user): bool
    {
        return $user->can('planning.create-timesheet');
    }

    /**
     * Determine whether the user can update the timesheet.
     */
    public function update(User $user, Timesheet $timesheet): bool
    {
        // Use the canBeEditedBy method from the model which has proper logic
        return $timesheet->canBeEditedBy($user);
    }

    /**
     * Determine whether the user can delete the timesheet.
     */
    public function delete(User $user, Timesheet $timesheet): bool
    {
        // Use the canBeEditedBy method from the model which has proper logic for deletions
        // Only draft timesheets can be deleted by the owner or admins
        return $timesheet->canBeEditedBy($user);
    }

    /**
     * Determine whether the user can submit the timesheet.
     */
    public function submit(User $user, Timesheet $timesheet): bool
    {
        return $user->can('planning.submit-timesheet') &&
               $timesheet->user_id === $user->id &&
               $timesheet->status === \Modules\CleaningJobs\Enums\PlanningCore\TimesheetStatus::DRAFT;
    }

    /**
     * Determine whether the user can approve the timesheet.
     */
    public function approve(User $user, Timesheet $timesheet): bool
    {
        return $user->can('planning.approve-timesheet') &&
               $timesheet->status === \Modules\CleaningJobs\Enums\PlanningCore\TimesheetStatus::SUBMITTED &&
               $timesheet->user_id !== $user->id; // Can't approve own timesheets
    }

    /**
     * Determine whether the user can reject the timesheet.
     */
    public function reject(User $user, Timesheet $timesheet): bool
    {
        return $user->can('planning.reject-timesheet') &&
               $timesheet->status === \Modules\CleaningJobs\Enums\PlanningCore\TimesheetStatus::SUBMITTED &&
               $timesheet->user_id !== $user->id; // Can't reject own timesheets
    }

    /**
     * Determine whether the user can export timesheets.
     */
    public function export(User $user): bool
    {
        return $user->can('planning.export-timesheets');
    }
}
