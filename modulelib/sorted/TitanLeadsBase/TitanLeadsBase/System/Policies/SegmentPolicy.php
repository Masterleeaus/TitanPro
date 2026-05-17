<?php

namespace App\Extensions\TitanLeads\System\Policies;

use App\Extensions\TitanLeads\System\Models\Whatsapp\Segment;
use App\Models\User;

class SegmentPolicy
{
    public function edit(User $user, Segment $item): bool
    {
        return $user->id === $item->user_id;
    }

    public function update(User $user, Segment $item): bool
    {
        return $user->id === $item->user_id;
    }

    public function delete(User $user, Segment $item): bool
    {
        return $user->id === $item->user_id;
    }
}
