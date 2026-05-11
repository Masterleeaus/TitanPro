<?php

namespace App\Extensions\TitanLeads\System\Policies;

use App\Extensions\TitanLeads\System\Models\Whatsapp\Contact;
use App\Models\User;

class ContactPolicy
{
    public function edit(User $user, Contact $item): bool
    {
        return $user->id === $item->user_id;
    }

    public function update(User $user, Contact $item): bool
    {
        return $user->id === $item->user_id;
    }

    public function delete(User $user, Contact $item): bool
    {
        return $user->id === $item->user_id;
    }
}
