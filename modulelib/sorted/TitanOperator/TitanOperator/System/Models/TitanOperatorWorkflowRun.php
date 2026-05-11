<?php

declare(strict_types=1);

namespace App\Extensions\TitanOperator\System\Models;

use Illuminate\Database\Eloquent\Model;

class TitanOperatorWorkflowRun extends Model
{
    protected $table = 'ext_titan_operator_workflow_runs';

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
}
