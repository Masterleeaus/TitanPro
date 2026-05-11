<?php

namespace App\Extensions\TitanOperator\System\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TitanOperatorHistory extends Model
{
    public $timestamps = false;

    protected $table = 'ext_titan_operator_histories';

    protected $fillable = [
        'user_id',
        'operator_id',
        'conversation_id',
        'message_id',
        'model',
        'role',
        'message',
        'type',
        'media_url',
        'media_name',
        'message_type',
        'content_type',
        'read_at',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(TitanOperatorConversation::class, 'conversation_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
