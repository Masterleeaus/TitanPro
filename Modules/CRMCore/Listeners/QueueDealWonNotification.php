<?php

namespace Modules\CRMCore\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\CRMCore\Events\DealWon;

class QueueDealWonNotification implements ShouldQueue
{
    public function handle(DealWon $event): void
    {
        // Queue any post-win notifications or downstream jobs here.
        // For example: notify assigned user, trigger automation, etc.
        // Extendable via automation manifest (crm.deal_won_create_project).
    }
}
