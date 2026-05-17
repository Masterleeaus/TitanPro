<?php

namespace Modules\ZeroPayModule\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\ZeroPayModule\Models\ZeroPaySession;

class PaymentPending
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly ZeroPaySession $session
    ) {}
}
