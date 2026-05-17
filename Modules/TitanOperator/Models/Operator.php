<?php

namespace Modules\TitanOperator\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Operator extends Model
{
    protected $table = 'tz_portal_operator_bots';

    protected $fillable = [
        'uuid',
        'user_id',
        'title',
        'bubble_message',
        'welcome_message',
        'instructions',
        'language',
        'ai_model',
        'ai_embedding_model',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function channels(): HasMany
    {
        return $this->hasMany(Channel::class, 'operator_id');
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class, 'operator_id');
    }
}
