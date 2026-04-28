<?php

namespace Modules\CRMCore\Support;

use Modules\CRMCore\Models\Lead;

class HostLeadBridge
{
    public static function modelClass(): string
    {
        return Lead::class;
    }

    public static function query()
    {
        return Lead::query();
    }

}
