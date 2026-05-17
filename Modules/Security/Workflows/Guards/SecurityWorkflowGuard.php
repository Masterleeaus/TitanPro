<?php

namespace Modules\Security\Workflows\Guards;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

class SecurityWorkflowGuard
{
    public function canApprove(Model $record, mixed $user = null): bool
    {
        $user = $user ?: auth()->user();

        if (! $user) {
            return false;
        }

        if (method_exists($user, 'can')) {
            return $user->can('edit_security') || $user->can('approve_security');
        }

        return Gate::allows('edit_security', $record) || Gate::allows('approve_security', $record);
    }

    public function canValidate(Model $record, mixed $user = null): bool
    {
        $user = $user ?: auth()->user();

        if (! $user) {
            return false;
        }

        if (method_exists($user, 'can')) {
            return $user->can('edit_security') || $user->can('validate_security');
        }

        return Gate::allows('edit_security', $record) || Gate::allows('validate_security', $record);
    }
}
