<?php

namespace Modules\CleaningJobs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicePart extends Model
{
    use HasFactory;

    protected $table = 'service_parts';
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
        return $this->hasMany('Modules\CleaningJobs\Models\ServiceTask','service_id');
    }
}
