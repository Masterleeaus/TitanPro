<?php

namespace Modules\JobManager\Entities;

    use HasFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


namespace ModulesJobManagerEntities;


namespace Modules\JobManager\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WOType extends Model
{
    use HasFactory;
    protected $fillable=[
        'type',
        'parent_id',
    ];
}
