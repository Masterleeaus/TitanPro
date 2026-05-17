<?php

namespace Modules\ZeroPayModule\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\ZeroPayModule\Events\PaymentCompleted;

class HandlePaymentCompleted implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(PaymentCompleted $event): void
    {
        // Process the completed payment.
    }
}
