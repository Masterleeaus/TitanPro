<?php

namespace Modules\TitanRewind\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RewindEvent extends Model
{
    public $timestamps = false;

    protected $table = 'titan_rewind_events';

    protected $fillable = [
        'company_id',
        'case_id',
        'event_type',
        'entity_type',
        'entity_id',
        'actor_type',
        'actor_id',
        'idempotency_key',
        'payload_json',
        'event_hash',
        'prev_event_hash',
        'created_at',
    ];

    protected $casts = [
        'payload_json' => 'array',
        'created_at' => 'datetime',
    ];

    public function rewindCase(): BelongsTo
    {
        return $this->belongsTo(RewindCase::class, 'case_id');
    }
}
