<?php

namespace Modules\GroundZeroOps\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\GroundZeroOps\Support\Traits\UsesScopedByCompany;

class Incident extends Model
{
    use HasFactory;
    use UsesScopedByCompany;

    protected $table = 'ground_zero_incidents';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'job_id',
        'reported_by',
        'severity',
        'status',
        'details',
        'logged_at',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'company_id' => 'integer',
        'job_id' => 'integer',
        'reported_by' => 'integer',
        'details' => 'array',
        'logged_at' => 'datetime',
    ];
}
