<?php

namespace Modules\CRMCore\Support;

use App\Models\Item;

class HostServiceBridge
{
    public static function modelClass(): string { return Item::class; }
    public static function query() { return Item::query(); }
    public static function match(?string $interest) { return $interest ? Item::query()->where('name', 'like', '%' . $interest . '%')->first() : null; }
}
