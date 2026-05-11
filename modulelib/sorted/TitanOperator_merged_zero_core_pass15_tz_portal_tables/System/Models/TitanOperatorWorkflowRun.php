<?php

declare(strict_types=1);

namespace App\Extensions\TitanOperator\System\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TitanOperatorWorkflowRun extends Model
{
    protected $table = 'tz_portal_operator_workflow_runs';

    protected $fillable = [
        'operator_id',
        'conversation_id',
        'workflow_key',
        'status',
        'input',
        'result',
        'confirmed_at',
        'executed_at',
        'completed_at',
    ];

    protected $casts = [
        'input' => 'array',
        'result' => 'array',
        'confirmed_at' => 'datetime',
        'executed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function operator(): BelongsTo
    {
        return $this->belongsTo(TitanOperator::class, 'operator_id');
    }
}
