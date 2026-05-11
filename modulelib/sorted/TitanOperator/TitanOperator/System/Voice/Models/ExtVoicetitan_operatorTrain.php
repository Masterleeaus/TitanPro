<?php

namespace App\Extensions\TitanOperator\System\Voice\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExtVoiceoperatorTrain extends Model
{
    use HasFactory;

    protected $fillable = [
        'operator_id',
        'user_id',

        'doc_id',
        'name',

        'type',
        'file',
        'url',
        'text',
        'trained_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
