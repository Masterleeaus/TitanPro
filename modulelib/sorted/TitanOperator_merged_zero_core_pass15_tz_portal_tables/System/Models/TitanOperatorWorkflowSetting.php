<?php

declare(strict_types=1);

namespace App\Extensions\TitanOperator\System\Models;

use Illuminate\Database\Eloquent\Model;

class TitanOperatorWorkflowSetting extends Model
{
    protected $table = 'tz_portal_operator_workflow_settings';

    protected $fillable = [
        'operator_id',
        'workflow_key',
        'enabled',
    ];

    protected $casts = [
        'enabled' => 'bool',
    ];
}
