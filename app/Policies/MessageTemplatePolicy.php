<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\MessageTemplate;
use Illuminate\Auth\Access\HandlesAuthorization;

class MessageTemplatePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:MessageTemplate');
    }

    public function view(AuthUser $authUser, MessageTemplate $messageTemplate): bool
    {
        return $authUser->can('View:MessageTemplate');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:MessageTemplate');
    }

    public function update(AuthUser $authUser, MessageTemplate $messageTemplate): bool
    {
        return $authUser->can('Update:MessageTemplate');
    }

    public function delete(AuthUser $authUser, MessageTemplate $messageTemplate): bool
    {
        return $authUser->can('Delete:MessageTemplate');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:MessageTemplate');
    }

    public function restore(AuthUser $authUser, MessageTemplate $messageTemplate): bool
    {
        return $authUser->can('Restore:MessageTemplate');
    }

    public function forceDelete(AuthUser $authUser, MessageTemplate $messageTemplate): bool
    {
        return $authUser->can('ForceDelete:MessageTemplate');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:MessageTemplate');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:MessageTemplate');
    }

    public function replicate(AuthUser $authUser, MessageTemplate $messageTemplate): bool
    {
        return $authUser->can('Replicate:MessageTemplate');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:MessageTemplate');
    }

}