<?php

namespace App\Extensions\TitanLeads\System\Policies;

use App\Extensions\TitanLeads\System\Models\Whatsapp\ContactList;
use App\Models\User;

class ContactListPolicy
{
    public function edit(User $user, ContactList $item): bool
    {
        return $user->id === $item->user_id;
    }

    public function update(User $user, ContactList $item): bool
    {
        return $user->id === $item->user_id;
    }

    public function delete(User $user, ContactList $item): bool
    {
        return $user->id === $item->user_id;
    }
}
