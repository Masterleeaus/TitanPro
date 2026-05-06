<?php

namespace Modules\CleaningJobs\Models\JobBoard;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Milestone extends Model
{
    use HasFactory;

    protected $fillable = [
        'id','project_id','title','status','cost','summary','progress','end_date','start_date'
    ];

    protected static function newFactory()
    {
        return \Workdo\JobBoard\Database\factories\MilestoneFactory::new();
    }
}
