<?php

namespace App\Extensions\TitanLeads\System\Policies;

use App\Extensions\TitanLeads\System\Models\Telegram\TelegramGroup;
use App\Models\User;

class TelegramGroupPolicy
{
    public function delete(User $user, TelegramGroup $item): bool
    {
        return $user->id === $item->user_id;
    }
}
