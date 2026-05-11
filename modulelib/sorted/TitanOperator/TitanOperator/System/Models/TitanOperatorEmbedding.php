<?php

namespace App\Extensions\TitanOperator\System\Models;

use App\Extensions\TitanOperator\System\Enums\EmbeddingTypeEnum;
use Illuminate\Database\Eloquent\Model;

class TitanOperatorEmbedding extends Model
{
    protected $table = 'ext_titan_operator_embeddings';

    protected $fillable = [
        'operator_id',
        'engine',
        'title',
        'file',
        'url',
        'content',
        'embedding',
        'type',
        'trained_at',
    ];

    protected $casts = [
        'embedding' => 'json',
        'type'      => EmbeddingTypeEnum::class,
    ];
}
