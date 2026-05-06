<?php

namespace Modules\PlanningCore\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PlanningCorePermissionSeeder extends Seeder
{
    /**
     * All PlanningCore permissions organized by category
     */
    protected array $permissions = [];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define all permissions
        $this->definePermissions();

        // Create permissions
        $this->createPermissions();

        // Update roles
        $this->updateRoles();

        $this->command->info('PlanningCore permissions seeded successfully!');
    }

    /**
     * Define all PlanningCore module permissions
     */
    protected function definePermissions(): void
    {
        $this->permissions = [
            // Project Management
            ['name' => 'planning.view-projects', 'description' => 'View all projects'],
            ['name' => 'planning.view-own-projects', 'description' => 'View own projects only'],
            ['name' => 'planning.create-project', 'description' => 'Create new projects'],
            ['name' => 'planning.edit-project', 'description' => 'Edit projects'],
            ['name' => 'planning.edit-own-project', 'description' => 'Edit own projects only'],
            ['name' => 'planning.delete-project', 'description' => 'Delete projects'],
            ['name' => 'planning.archive-project', 'description' => 'Archive projects'],
            ['name' => 'planning.duplicate-project', 'description' => 'Duplicate projects'],
            ['name' => 'planning.export-projects', 'description' => 'Export projects data'],

            // Project Team Management
            ['name' => 'planning.manage-project-team', 'description' => 'Manage project team members'],
            ['name' => 'planning.view-project-members', 'description' => 'View project team members'],
            ['name' => 'planning.add-project-member', 'description' => 'Add team members to projects'],
            ['name' => 'planning.remove-project-member', 'description' => 'Remove team members from projects'],
            ['name' => 'planning.update-member-role', 'description' => 'Update team member roles'],

            // Project Tasks
            ['name' => 'planning.view-project-tasks', 'description' => 'View project tasks'],
            ['name' => 'planning.create-project-task', 'description' => 'Create project tasks'],
            ['name' => 'planning.edit-project-task', 'description' => 'Edit project tasks'],
            ['name' => 'planning.edit-own-task', 'description' => 'Edit own tasks only'],
            ['name' => 'planning.delete-project-task', 'description' => 'Delete project tasks'],
            ['name' => 'planning.assign-project-task', 'description' => 'Assign project tasks to team members'],
            ['name' => 'planning.complete-project-task', 'description' => 'Mark project tasks as complete'],
            ['name' => 'planning.start-project-task', 'description' => 'Start working on project tasks'],
            ['name' => 'planning.reorder-project-tasks', 'description' => 'Reorder project tasks'],

            // Project Dashboard & Reports
            ['name' => 'planning.view-project-dashboard', 'description' => 'View project dashboard'],
            ['name' => 'planning.view-project-reports', 'description' => 'View project reports'],
            ['name' => 'planning.view-time-reports', 'description' => 'View time tracking reports'],
            ['name' => 'planning.view-budget-reports', 'description' => 'View budget reports'],
            ['name' => 'planning.view-resource-reports', 'description' => 'View resource utilization reports'],
            ['name' => 'planning.export-reports', 'description' => 'Export project reports'],

            // Timesheets
            ['name' => 'planning.view-timesheets', 'description' => 'View all timesheets'],
            ['name' => 'planning.view-own-timesheets', 'description' => 'View own timesheets only'],
            ['name' => 'planning.create-timesheet', 'description' => 'Create timesheets'],
            ['name' => 'planning.edit-timesheet', 'description' => 'Edit timesheets'],
            ['name' => 'planning.edit-own-timesheet', 'description' => 'Edit own timesheets only'],
            ['name' => 'planning.delete-timesheet', 'description' => 'Delete timesheets'],
            ['name' => 'planning.submit-timesheet', 'description' => 'Submit timesheets for approval'],
            ['name' => 'planning.approve-timesheet', 'description' => 'Approve timesheets'],
            ['name' => 'planning.reject-timesheet', 'description' => 'Reject timesheets'],
            ['name' => 'planning.export-timesheets', 'description' => 'Export timesheet data'],

            // Resource Management
            ['name' => 'planning.view-resources', 'description' => 'View resource allocations'],
            ['name' => 'planning.manage-resources', 'description' => 'Manage resource allocations'],
            ['name' => 'planning.allocate-resource', 'description' => 'Allocate resources to projects'],
            ['name' => 'planning.view-resource-capacity', 'description' => 'View resource capacity planning'],
            ['name' => 'planning.view-resource-schedule', 'description' => 'View resource schedules'],
            ['name' => 'planning.check-resource-availability', 'description' => 'Check resource availability'],

            // Project Status Management
            ['name' => 'planning.view-project-statuses', 'description' => 'View project statuses'],
            ['name' => 'planning.manage-project-statuses', 'description' => 'Manage project statuses'],
            ['name' => 'planning.create-project-status', 'description' => 'Create new project statuses'],
            ['name' => 'planning.edit-project-status', 'description' => 'Edit project statuses'],
            ['name' => 'planning.delete-project-status', 'description' => 'Delete project statuses'],
            ['name' => 'planning.toggle-project-status', 'description' => 'Enable/disable project statuses'],
            ['name' => 'planning.sort-project-statuses', 'description' => 'Reorder project statuses'],

            // Project Settings
            ['name' => 'planning.manage-project-settings', 'description' => 'Manage project module settings'],
            ['name' => 'planning.view-project-settings', 'description' => 'View project module settings'],
        ];
    }

    /**
     * Create all permissions in the database
     */
    protected function createPermissions(): void
    {
        foreach ($this->permissions as $permission) {
            try {
                $perm = Permission::firstOrCreate(
                    [
                        'name' => $permission['name'],
                        'guard_name' => 'web',
                    ],
                    [
                        'module' => 'PlanningCore',
                        'description' => $permission['description'],
                    ]
                );

                if ($perm->wasRecentlyCreated) {
                    $this->command->info("Created permission: {$permission['name']}");
                } else {
                    // Update the module and description if permission already exists
                    $perm->update([
                        'module' => 'PlanningCore',
                        'description' => $permission['description'],
                    ]);
                    $this->command->info("Updated permission: {$permission['name']}");
                }
            } catch (\Exception $e) {
                $this->command->error("Failed to create/update permission {$permission['name']}: ".$e->getMessage());
            }
        }
    }

    /**
     * Update existing roles with PlanningCore permissions
     */
    protected function updateRoles(): void
    {
        // Super Admin - Full access
        $this->updateSuperAdminRole();

        // Admin - Full project management
        $this->updateAdminRole();

        // Project Manager - Full project access
        $this->updateProjectManagerRole();

        // Team Leader - Limited management
        $this->updateTeamLeaderRole();

        // Employee - Basic access
        $this->updateEmployeeRole();

        // Client - View only access
        $this->updateClientRole();
    }

    /**
     * Update Super Admin role
     */
    protected function updateSuperAdminRole(): void
    {
        try {
            $role = Role::where('name', 'super_admin')->first();
            if ($role) {
                $permissions = Permission::where('name', 'like', 'planning.%')->pluck('name')->toArray();
                if (! empty($permissions)) {
                    $role->givePermissionTo($permissions);
                    $this->command->info('Updated Super Admin role with all PlanningCore permissions ('.count($permissions).' permissions)');
                }
            }
        } catch (\Exception $e) {
            $this->command->error('Failed to update Super Admin role: '.$e->getMessage());
        }
    }

    /**
     * Update Admin role
     */
    protected function updateAdminRole(): void
    {
        try {
            $role = Role::where('name', 'admin')->first();
            if ($role) {
                $permissions = Permission::where('name', 'like', 'planning.%')->pluck('name')->toArray();
                $role->givePermissionTo($permissions);
                $this->command->info('Updated Admin role with PlanningCore permissions');
            }
        } catch (\Exception $e) {
            $this->command->error('Failed to update Admin role: '.$e->getMessage());
        }
    }

    /**
     * Update Project Manager role
     */
    protected function updateProjectManagerRole(): void
    {
        try {
            $role = Role::where('name', 'project_manager')->first();
            if ($role) {
                $permissions = [
                    // Full project access
                    'planning.view-projects',
                    'planning.create-project',
                    'planning.edit-project',
                    'planning.delete-project',
                    'planning.archive-project',
                    'planning.duplicate-project',
                    'planning.export-projects',

                    // Team management
                    'planning.manage-project-team',
                    'planning.view-project-members',
                    'planning.add-project-member',
                    'planning.remove-project-member',
                    'planning.update-member-role',

                    // Tasks
                    'planning.view-project-tasks',
                    'planning.create-project-task',
                    'planning.edit-project-task',
                    'planning.delete-project-task',
                    'planning.assign-project-task',
                    'planning.complete-project-task',
                    'planning.start-project-task',
                    'planning.reorder-project-tasks',

                    // Dashboard & Reports
                    'planning.view-project-dashboard',
                    'planning.view-project-reports',
                    'planning.view-time-reports',
                    'planning.view-budget-reports',
                    'planning.view-resource-reports',
                    'planning.export-reports',

                    // Timesheets
                    'planning.view-timesheets',
                    'planning.approve-timesheet',
                    'planning.reject-timesheet',
                    'planning.export-timesheets',

                    // Resources
                    'planning.view-resources',
                    'planning.manage-resources',
                    'planning.allocate-resource',
                    'planning.view-resource-capacity',
                    'planning.view-resource-schedule',
                    'planning.check-resource-availability',

                    // Status management
                    'planning.view-project-statuses',
                ];
                $role->givePermissionTo($permissions);
                $this->command->info('Updated Project Manager role with PlanningCore permissions');
            }
        } catch (\Exception $e) {
            $this->command->error('Failed to update Project Manager role: '.$e->getMessage());
        }
    }

    /**
     * Update Team Leader role
     */
    protected function updateTeamLeaderRole(): void
    {
        try {
            $role = Role::where('name', 'team_leader')->first();
            if ($role) {
                $permissions = [
                    // View projects
                    'planning.view-projects',
                    'planning.edit-own-project',

                    // Team viewing
                    'planning.view-project-members',

                    // Tasks
                    'planning.view-project-tasks',
                    'planning.create-project-task',
                    'planning.edit-project-task',
                    'planning.assign-project-task',
                    'planning.complete-project-task',
                    'planning.start-project-task',

                    // Dashboard & Reports
                    'planning.view-project-dashboard',
                    'planning.view-project-reports',
                    'planning.view-time-reports',
                    'planning.view-resource-reports',

                    // Timesheets
                    'planning.view-timesheets',
                    'planning.create-timesheet',
                    'planning.edit-own-timesheet',
                    'planning.submit-timesheet',

                    // Resources
                    'planning.view-resources',
                    'planning.view-resource-capacity',
                    'planning.view-resource-schedule',
                ];
                $role->givePermissionTo($permissions);
                $this->command->info('Updated Team Leader role with PlanningCore permissions');
            }
        } catch (\Exception $e) {
            $this->command->error('Failed to update Team Leader role: '.$e->getMessage());
        }
    }

    /**
     * Update Employee role
     */
    protected function updateEmployeeRole(): void
    {
        try {
            $role = Role::where('name', 'employee')->first();
            if ($role) {
                $permissions = [
                    // View own projects
                    'planning.view-own-projects',

                    // View team
                    'planning.view-project-members',

                    // Tasks
                    'planning.view-project-tasks',
                    'planning.edit-own-task',
                    'planning.complete-project-task',
                    'planning.start-project-task',

                    // Timesheets
                    'planning.view-own-timesheets',
                    'planning.create-timesheet',
                    'planning.edit-own-timesheet',
                    'planning.submit-timesheet',

                    // View schedules
                    'planning.view-resource-schedule',
                ];
                $role->givePermissionTo($permissions);
                $this->command->info('Updated Employee role with PlanningCore permissions');
            }
        } catch (\Exception $e) {
            $this->command->error('Failed to update Employee role: '.$e->getMessage());
        }
    }

    /**
     * Update Client role
     */
    protected function updateClientRole(): void
    {
        try {
            $role = Role::where('name', 'client')->first();
            if ($role) {
                $permissions = [
                    'planning.view-own-projects',
                    'planning.view-project-tasks',
                    'planning.view-project-reports',
                ];
                $role->givePermissionTo($permissions);
                $this->command->info('Updated Client role with PlanningCore permissions');
            }
        } catch (\Exception $e) {
            $this->command->error('Failed to update Client role: '.$e->getMessage());
        }
    }
}
