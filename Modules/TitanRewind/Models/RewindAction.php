<?php

namespace Modules\TitanRewind\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RewindAction extends Model
{
    protected $table = 'titan_rewind_actions';

    protected $fillable = [
        'company_id',
        'case_id',
        'fix_id',
        'action_type',
        'target_type',
        'target_id',
        'before_json',
        'after_json',
        'executed_by_type',
        'executed_by_id',
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

    public function rewindCase(): BelongsTo
    {
        return $this->belongsTo(RewindCase::class, 'case_id');
    }

    public function fix(): BelongsTo
    {
        return $this->belongsTo(RewindFix::class, 'fix_id');
    }
}
