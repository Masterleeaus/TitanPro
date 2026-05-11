<?php

namespace Modules\TitanOperator\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class KnowledgeBaseEmbeddingPipeline
{
    public function ingest(int $operatorId, string $title, ?string $content): void
    {
        if (! Schema::hasTable('tz_portal_operator_embeddings') || trim((string) $content) === '') {
            return;
        }

        DB::table('tz_portal_operator_embeddings')->insert([
            'operator_id' => $operatorId,
            'engine' => 'titanzero-vector',
            'title' => $title,
            'content' => $content,
            'type' => 'text',
            'trained_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
