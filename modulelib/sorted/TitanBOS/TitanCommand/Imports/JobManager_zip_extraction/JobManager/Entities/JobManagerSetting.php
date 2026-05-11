<?php

namespace Modules\JobManager\Entities;

use Illuminate\Database\Eloquent\Model;


namespace ModulesJobManagerEntities;


namespace Modules\JobManager\Entities;

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
