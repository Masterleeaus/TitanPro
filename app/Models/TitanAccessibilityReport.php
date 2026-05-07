<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TitanAccessibilityReport extends Model
{
    protected $fillable = [
        'platform_setting_id',
        'theme',
        'tokens',
        'checks',
        'summary',
        'dismissed_checks',
        'applied_fixes',
    ];

    protected $casts = [
        'tokens' => 'array',
        'checks' => 'array',
        'summary' => 'array',
        'dismissed_checks' => 'array',
        'applied_fixes' => 'array',
    ];
}
