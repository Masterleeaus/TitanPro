<?php

namespace App\Extensions\TitanCommand\System\JobManager\Entities;

    use HasFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;






class ServicePart extends Model
{
    protected $fillable=[
        'title',
        'sku',
        'unit',
        'price',
        'description',
        'type',
        'parent_id',
    };

    public function serviceTasks()
    {
        return $this->hasMany('App\Extensions\TitanCommand\System\JobManager\Entities\ServiceTask','service_id');
    }
}
