<?php

namespace Modules\Security\Policies\Workflow;

use Illuminate\Contracts\Auth\Authenticatable;

class SecurityWorkflowPolicy
{
    public function transition(Authenticatable $user, string $transition): bool
    {
        return match ($transition) {
            'approve' => method_exists($user, 'can') && $user->can('security.approve'),
            'validate' => method_exists($user, 'can') && $user->can('security.validate'),
            default => method_exists($user, 'can') && $user->can('security.update'),
        };
    }
}
