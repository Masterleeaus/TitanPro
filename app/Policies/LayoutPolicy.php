<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use LaraZeus\DynamicDashboard\Models\Layout;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy for LaraZeus DynamicDashboard layouts.
 *
 * Permission strings follow FilamentShield's generated format:
 * {action}_{resource_snake_case}  (e.g. view_any_layout, create_layout).
 * Run `php artisan shield:generate --resource=LayoutResource` on the server
 * to create the corresponding permission records in the database.
 */
class LayoutPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('view_any_layout');
    }

    public function view(AuthUser $authUser, Layout $layout): bool
    {
        return $authUser->can('view_layout');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create_layout');
    }

    public function update(AuthUser $authUser, Layout $layout): bool
    {
        return $authUser->can('update_layout');
    }

    public function delete(AuthUser $authUser, Layout $layout): bool
    {
        return $authUser->can('delete_layout');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('delete_any_layout');
    }

    public function restore(AuthUser $authUser, Layout $layout): bool
    {
        return $authUser->can('restore_layout');
    }

    public function forceDelete(AuthUser $authUser, Layout $layout): bool
    {
        return $authUser->can('force_delete_layout');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('force_delete_any_layout');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restore_any_layout');
    }

    public function replicate(AuthUser $authUser, Layout $layout): bool
    {
        return $authUser->can('replicate_layout');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder_layout');
    }
}