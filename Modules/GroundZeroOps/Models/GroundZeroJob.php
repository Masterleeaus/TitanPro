<?php

namespace Modules\GroundZeroOps\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\GroundZeroOps\Support\Traits\UsesScopedByCompany;

class GroundZeroJob extends Model
{
    use HasFactory;
    use UsesScopedByCompany;

    protected $table = 'ground_zero_jobs';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'title',
        'description',
        'status',
        'assigned_technician_id',
        'created_by',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'company_id' => 'integer',
        'assigned_technician_id' => 'integer',
        'created_by' => 'integer',
    ];
}
