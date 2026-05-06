<?php

namespace Modules\BookingModule\Support\Compat;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ZoneCompat extends Model
{
    protected $table = 'zones';
    protected $guarded = [];

    public function scopeOfStatus(Builder $query, $status): Builder
    {
        return $query->where('status', $status);
    }
}
