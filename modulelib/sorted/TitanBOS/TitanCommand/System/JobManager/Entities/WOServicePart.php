<?php

namespace App\Extensions\TitanCommand\System\JobManager\Entities;

    use HasFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;






class WOServicePart extends Model
{
    protected $fillable=[
        'wo_id',
        'service_part_id',
        'quantity',
        'amount',
        'type',
        'description',
    };

    public function serviceParts()
    {
        return $this->hasOne('App\Extensions\TitanCommand\System\JobManager\Entities\ServicePart','id','service_part_id');
    }

    public function serviceTasks()
    {
        return $this->hasMany('App\Extensions\TitanCommand\System\JobManager\Entities\ServiceTask','service_id','id');
    }
}
