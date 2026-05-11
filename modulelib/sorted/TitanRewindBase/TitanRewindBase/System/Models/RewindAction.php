<?php

namespace App\Extensions\TitanPay\System\Models;

use Illuminate\Database\Eloquent\Model;

class RewindAction extends Model
{
    protected $table = 'boxed_automation_actions';

    protected $fillable = [
        'company_id','user_id',
        'case_id','fix_id',
        'action_type',
        'target_type','target_id',
        'before_json','after_json',
        'executed_by_type','executed_by_id',
        'executed_at',
        'success',
        'error_text',
    ];

    protected $casts = [
        'before_json' => 'array',
        'after_json' => 'array',
        'executed_at' => 'datetime',
        'success' => 'boolean',
    ];
}
