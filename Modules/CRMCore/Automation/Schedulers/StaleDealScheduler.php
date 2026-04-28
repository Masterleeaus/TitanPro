<?php

namespace Modules\CRMCore\Automation\Schedulers;

class StaleDealScheduler
{
    public function cadence(): string
    {
        return 'daily';
    }
}
