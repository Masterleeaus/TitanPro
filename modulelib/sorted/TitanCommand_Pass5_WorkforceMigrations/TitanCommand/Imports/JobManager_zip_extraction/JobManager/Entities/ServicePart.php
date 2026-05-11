<?php

namespace Modules\JobManager\Entities;

    use HasFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


namespace ModulesJobManagerEntities;


namespace Modules\JobManager\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicePart extends Model
{
    use HasFactory;
    protected $fillable=[
        'title',
        'sku',
        'unit',
        'price',
        'description',
        'type',
        'parent_id',
    ];

    public function serviceTasks()
    {
        return $this->hasMany('Modules\JobManager\Entities\ServiceTask','service_id');
    }
}
