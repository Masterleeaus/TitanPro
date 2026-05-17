<?php

namespace Modules\GroundZeroOps\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\GroundZeroOps\Support\Traits\UsesScopedByCompany;

class Shift extends Model
{
    use HasFactory;
    use UsesScopedByCompany;

    protected $table = 'ground_zero_shifts';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'technician_id',
        'status',
        'started_at',
        'ended_at',
        'started_by',
        'ended_by',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'company_id' => 'integer',
        'technician_id' => 'integer',
        'started_by' => 'integer',
        'ended_by' => 'integer',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];
}
