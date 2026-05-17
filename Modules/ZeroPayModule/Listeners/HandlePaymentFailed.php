<?php

namespace Modules\ZeroPayModule\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\ZeroPayModule\Events\PaymentFailed;

class HandlePaymentFailed implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(PaymentFailed $event): void
    {
        // Handle the failed payment (e.g. notify, requeue, log).
    }
}
