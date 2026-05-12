<?php

namespace Modules\CRMCore\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Modules\CRMCore\Models\Deal;

class DealLost
{
    use Dispatchable;

    public function __construct(public Deal $deal)
    {
    }
}
