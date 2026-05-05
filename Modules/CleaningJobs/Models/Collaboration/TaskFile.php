<?php

namespace Modules\CleaningJobs\Models\JobBoard;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaskFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'file','name','extension','file_size','created_by','task_id','user_type'
    ];

    protected static function newFactory()
    {
        return \Workdo\JobBoard\Database\factories\TaskFileFactory::new();
    }
}
