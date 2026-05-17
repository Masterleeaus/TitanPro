<?php

namespace Modules\GroundZeroOps\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\GroundZeroOps\Support\Traits\UsesScopedByCompany;

class Dispatch extends Model
{
    use HasFactory;
    use UsesScopedByCompany;

    protected $table = 'ground_zero_dispatches';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'job_id',
        'technician_id',
        'status',
        'assigned_by',
        'assigned_at',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'company_id' => 'integer',
        'job_id' => 'integer',
        'technician_id' => 'integer',
        'assigned_by' => 'integer',
        'assigned_at' => 'datetime',
    ];
}
