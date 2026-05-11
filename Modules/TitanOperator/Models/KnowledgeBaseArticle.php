<?php

namespace Modules\TitanOperator\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\TitanOperator\Services\KnowledgeBaseEmbeddingPipeline;

class KnowledgeBaseArticle extends Model
{
    protected $table = 'tz_portal_operator_knowledge_base_articles';

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'content',
        'is_featured',
        'operators',
    ];

    protected $casts = [
        'operators' => 'array',
        'is_featured' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(function (self $article): void {
            $operatorIds = array_filter((array) $article->operators);
            $operatorId = $operatorIds[0] ?? null;

            if (! is_numeric($operatorId)) {
                return;
            }

            app(KnowledgeBaseEmbeddingPipeline::class)->ingest(
                (int) $operatorId,
                (string) ($article->title ?? ''),
                $article->content
            );
        });
    }
}
