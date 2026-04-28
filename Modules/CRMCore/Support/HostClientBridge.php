<?php

namespace Modules\CRMCore\Support;

use App\Models\Customer;

class HostClientBridge
{
    public static function modelClass(): string { return Customer::class; }
    public static function query() { return Customer::query(); }
}
