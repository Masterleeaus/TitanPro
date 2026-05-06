<?php

namespace Modules\CleaningJobs\Models\JobBoard;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'user_id', 'project_id', 'is_active',
    ];

    protected static function newFactory()
    {
        return \Workdo\JobBoard\Database\factories\UserProjectFactory::new();
    }
}
