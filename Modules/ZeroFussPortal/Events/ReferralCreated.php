<?php

namespace Modules\ZeroFussPortal\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\ZeroFussPortal\Models\Referral;

class ReferralCreated
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public Referral $referral)
    {
    }
}
