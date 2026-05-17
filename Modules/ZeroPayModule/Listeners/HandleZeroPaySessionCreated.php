<?php

namespace Modules\ZeroPayModule\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\ZeroPayModule\Events\ZeroPaySessionCreated;

class HandleZeroPaySessionCreated implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(ZeroPaySessionCreated $event): void
    {
        // Process the newly created ZeroPay session.
    }
}
