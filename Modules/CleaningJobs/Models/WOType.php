<?php

namespace Modules\CleaningJobs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WOType extends Model
{
    use HasFactory;

    protected $table = 'wo_types';

    protected $fillable = [
        'type', 'name', 'description', 'parent_id',
    ];
}
