<?php

namespace Modules\TitanRewind\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RewindCase extends Model
{
    protected $table = 'titan_rewind_cases';

    protected $fillable = [
        'company_id',
        'case_key',
        'title',
        'status',
        'severity',
        'source_type',
        'source_id',
        'entity_type',
        'entity_id',
        'detected_at',
        'meta_json',
        'resolved_at',
        'resolved_by_type',
        'resolved_by_id',
    ];

    protected $casts = [
        'detected_at' => 'datetime',
        'resolved_at' => 'datetime',
        'meta_json' => 'array',
    ];

    public function events(): HasMany
    {
        return $this->hasMany(RewindEvent::class, 'case_id');
    }

    public function fixes(): HasMany
    {
        return $this->hasMany(RewindFix::class, 'case_id');
    }

    public function actions(): HasMany
    {
        return $this->hasMany(RewindAction::class, 'case_id');
    }
}
