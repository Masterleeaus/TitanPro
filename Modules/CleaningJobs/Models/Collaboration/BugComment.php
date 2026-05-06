<?php

namespace Modules\CleaningJobs\Models\JobBoard;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BugComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment','created_by','bug_id','user_type'
    ];

    protected static function newFactory()
    {
        return \Workdo\JobBoard\Database\factories\BugCommentFactory::new();
    }
    public function user(){
        return $this->hasOne('App\Models\User','id','created_by');
    }
}
