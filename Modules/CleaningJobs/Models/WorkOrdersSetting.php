<?php

namespace Modules\CleaningJobs\Models;

use Illuminate\Database\Eloquent\Model;

class WorkOrdersSetting extends Model
{
    protected $table = 'workorders_settings';
    protected $guarded = [];

    public static function getOrCreate(): self
    {
        return static::query()->first() ?? static::create([]);
    }
}
