<?php

namespace App\Extensions\TitanOperator\System\Models;

use Illuminate\Database\Eloquent\Model;

class TitanOperatorKnowledgeBaseArticle extends Model
{
    protected $table = 'ext_titan_operator_knowledge_base_articles';

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'content',
        'is_featured',
        'operators',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'operators'    => 'array',
    ];
}
