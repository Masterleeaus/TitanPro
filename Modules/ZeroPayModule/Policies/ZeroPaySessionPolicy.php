<?php

namespace Modules\ZeroPayModule\Policies;

class ZeroPaySessionPolicy
{
    public function viewAny($user): bool
    {
        return $user->can('zeropay.view');
    }

    public function view($user, $record): bool
    {
        return $user->can('zeropay.view') && $record->company_id === $user->company_id;
    }

    public function create($user): bool
    {
        return $user->can('zeropay.create');
    }

    public function update($user, $record): bool
    {
        return $user->can('zeropay.update') && $record->company_id === $user->company_id;
    }

    public function delete($user, $record): bool
    {
        return $user->can('zeropay.delete') && $record->company_id === $user->company_id;
    }
}
