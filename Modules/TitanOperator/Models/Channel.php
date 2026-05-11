<?php

namespace Modules\TitanOperator\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Channel extends Model
{
    protected $table = 'tz_portal_operator_channels';

    protected $fillable = [
        'user_id',
        'operator_id',
        'channel',
        'credentials',
        'payload',
        'connected_at',
    ];

    protected $casts = [
        'credentials' => 'array',
        'payload' => 'array',
        'connected_at' => 'datetime',
    ];

    public function operator(): BelongsTo
    {
        return $this->belongsTo(Operator::class, 'operator_id');
    }
}
