<?php

namespace Modules\CRMCore\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\CRMCore\Events\DealLost;

class QueueDealLostNotification implements ShouldQueue
{
    public function handle(DealLost $event): void
    {
        // Queue any post-loss notifications or downstream jobs here.
        // For example: notify assigned user, log in CRM activity, etc.
    }
}
