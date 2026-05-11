<?php

namespace App\Extensions\TitanOperator\System\Voice\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExtVoicechabotConversation extends Model
{
    use HasFactory;

    protected $fillable = ['operator_uuid', 'conversation_id'];

    public function chat_histories(): HasMany
    {
        return $this->hasMany(ExtVoiceoperatorHistory::class, 'conversation_id');
    }

    public function titan_operator(): BelongsTo
    {
        return $this->belongsTo(ExtVoiceTitanOperator::class, 'operator_uuid', 'uuid');
    }
}
