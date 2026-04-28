<?php

namespace Modules\CRMCore\Support;

use Modules\CRMCore\Models\Deal;

class HostDealBridge
{
    public static function modelClass(): string
    {
        return Deal::class;
    }

    public static function query()
    {
        return Deal::query();
    }

}
