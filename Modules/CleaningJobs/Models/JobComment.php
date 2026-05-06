<?php

namespace Modules\CleaningJobs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobComment extends Model
{
    use HasFactory;

    protected $table = 'cleaning_job_comments';
    protected $fillable = ['commentable_type','commentable_id','user_id','body','is_internal'];
    protected $casts = ['is_internal' => 'boolean'];

    public function commentable() { return $this->morphTo(); }
    public function user() { return $this->belongsTo(config('cleaningjobs.models.user', \App\Models\User::class)); }
}
