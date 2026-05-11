<?php

declare(strict_types=1);

namespace App\Extensions\TitanOperator\System\Models;

use Illuminate\Database\Eloquent\Model;

class TitanOperatorToolSetting extends Model
{
    protected $table = 'tz_portal_operator_tool_settings';

    protected $fillable = [
        'operator_id',
        'tool_key',
        'enabled',
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];
}
