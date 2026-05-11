<?php

namespace App\Extensions\TitanCommand\System\JobManager\Entities;

use Illuminate\Database\Eloquent\Model;






class JobManagerSetting extends Model
{
    protected $table = 'jobmanager_settings';
    protected $guarded = [];

    public static function getOrCreate(): self
    {
        return static::query()->first() ?? static::create([]);
    }
}
