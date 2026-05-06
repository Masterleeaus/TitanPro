<?php

namespace Modules\CleaningJobs\Policies\PlanningCore;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\CleaningJobs\Models\PlanningCore\Project;

class ProjectPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any projects.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('planning.view-projects') || $user->can('planning.view-own-projects');
    }

    /**
     * Determine whether the user can view the project.
     */
    public function view(User $user, Project $project): bool
    {
        if ($user->can('planning.view-projects')) {
            return true;
        }

        if ($user->can('planning.view-own-projects')) {
            // Check if user is project manager or team member
            return $project->project_manager_id === $user->id ||
                   $project->members()->where('user_id', $user->id)->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can create projects.
     */
    public function create(User $user): bool
    {
        return $user->can('planning.create-project');
    }

    /**
     * Determine whether the user can update the project.
     */
    public function update(User $user, Project $project): bool
    {
        if ($user->can('planning.edit-project')) {
            return true;
        }

        if ($user->can('planning.edit-own-project')) {
            return $project->project_manager_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the project.
     */
    public function delete(User $user, Project $project): bool
    {
        return $user->can('planning.delete-project');
    }

    /**
     * Determine whether the user can archive the project.
     */
    public function archive(User $user, Project $project): bool
    {
        return $user->can('planning.archive-project');
    }

    /**
     * Determine whether the user can duplicate the project.
     */
    public function duplicate(User $user, Project $project): bool
    {
        return $user->can('planning.duplicate-project');
    }

    /**
     * Determine whether the user can manage team members.
     */
    public function manageTeam(User $user, Project $project): bool
    {
        return $user->can('planning.manage-project-team') ||
               ($user->can('planning.edit-own-project') && $project->project_manager_id === $user->id);
    }

    /**
     * Determine whether the user can view team members.
     */
    public function viewTeam(User $user, Project $project): bool
    {
        return $user->can('planning.view-project-members') ||
               $this->view($user, $project);
    }

    /**
     * Determine whether the user can add team members.
     */
    public function addMember(User $user, Project $project): bool
    {
        return $user->can('planning.add-project-member') ||
               ($user->can('planning.manage-project-team') && $project->project_manager_id === $user->id);
    }

    /**
     * Determine whether the user can remove team members.
     */
    public function removeMember(User $user, Project $project): bool
    {
        return $user->can('planning.remove-project-member') ||
               ($user->can('planning.manage-project-team') && $project->project_manager_id === $user->id);
    }

    /**
     * Determine whether the user can export projects.
     */
    public function export(User $user): bool
    {
        return $user->can('planning.export-projects');
    }

    /**
     * Determine whether the user can view project dashboard.
     */
    public function viewDashboard(User $user): bool
    {
        return $user->can('planning.view-project-dashboard');
    }

    /**
     * Determine whether the user can view project reports.
     */
    public function viewReports(User $user): bool
    {
        return $user->can('planning.view-project-reports');
    }
}
