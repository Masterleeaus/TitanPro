<?php

namespace Modules\CleaningJobs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobFile extends Model
{
    use HasFactory;

    protected $table = 'cleaning_job_files';
    protected $fillable = ['fileable_type','fileable_id','disk','path','name','extension','size','uploaded_by_id'];

    public function fileable() { return $this->morphTo(); }
    public function uploader() { return $this->belongsTo(config('cleaningjobs.models.user', \App\Models\User::class), 'uploaded_by_id'); }
}
