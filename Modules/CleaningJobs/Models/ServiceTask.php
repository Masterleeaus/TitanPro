<?php

namespace Modules\CleaningJobs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceTask extends Model
{
    use HasFactory;

    protected $table = 'service_tasks';
    protected $fillable=[
        'service_id',
        'task',
        'duration',
        'description',
    ];
}
