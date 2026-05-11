<?php

namespace App\Extensions\TitanPay\System\Models;

use Illuminate\Database\Eloquent\Model;

class RewindEvent extends Model
{
    protected $table = 'boxed_automation_events';

    protected $fillable = [
        'company_id','user_id',
        'case_id',
        'event_type',
        'entity_type','entity_id',
        'actor_type','actor_id',
        'idempotency_key',
        'payload_json',
        'event_hash','prev_event_hash',
        'created_at',
    ];

    public $timestamps = false;

    protected $casts = [
        'payload_json' => 'array',
    ];
}
