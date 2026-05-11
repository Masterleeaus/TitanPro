<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * TitanZeroChat — Known Gap #2: pgvector Migration Path
 *
 * Migrates embedding storage from the current json-column approach (pdf_data.vector)
 * to native pgvector for semantic similarity search at scale.
 *
 * PREREQUISITES:
 *   - PostgreSQL >= 14 with the pgvector extension installed
 *   - Run: CREATE EXTENSION IF NOT EXISTS vector; in your DB
 *   - Set DB_CONNECTION=pgsql in .env
 *
 * STATUS: STUB — this migration is safe to run but creates the new table only.
 *         Backfill of existing embeddings is a separate job (see TzEmbeddingBackfillJob).
 *
 * STEPS TO ACTIVATE:
 *   1. Install pgvector on your DB server
 *   2. Uncomment the DB::statement() calls below
 *   3. Run: php artisan migrate --path=app/Modules/TitanZeroChat/Database/Migrations
 *   4. Dispatch TzEmbeddingBackfillJob to migrate existing pdf_data rows
 *   5. Update VectorService::getMostSimilarText() to query tzc_embeddings instead of pdf_data
 */
return new class extends Migration
{
    public function up(): void
    {
        // Step 1: Enable pgvector extension (requires superuser or rds_superuser)
        // DB::statement('CREATE EXTENSION IF NOT EXISTS vector');

        // Step 2: Create the new unified embeddings table
        // Replace pdf_data.vector (json) with native vector(1536) for ada-002
        // or vector(3072) for text-embedding-3-large
        if (!Schema::hasTable('tzc_embeddings')) {
            Schema::create('tzc_embeddings', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('team_id')->index();          // tenant key
                $table->string('source_type', 50)->index();              // 'chatbot' | 'webchat' | 'file'
                $table->unsignedBigInteger('source_id')->index();        // chatbot_id, chat_id, etc.
                $table->unsignedBigInteger('chunk_index')->default(0);
                $table->mediumText('content');                           // raw text chunk
                $table->string('embedding_model', 100)->default('text-embedding-ada-002');
                // vector column — uncomment when pgvector is enabled:
                // $table->rawColumn('embedding vector(1536)');
                $table->text('embedding_json')->nullable();              // interim: json fallback
                $table->unsignedInteger('token_count')->nullable();
                $table->json('meta')->nullable();                        // source URL, page, etc.
                $table->timestamps();

                $table->index(['team_id', 'source_type', 'source_id'], 'tzc_embed_source_idx');
            });
        }

        // Step 3: Add native vector index (HNSW — fastest for approximate nearest neighbor)
        // Run AFTER backfill is complete:
        // DB::statement('CREATE INDEX tzc_embed_hnsw_idx ON tzc_embeddings USING hnsw (embedding vector_cosine_ops)');
    }

    public function down(): void
    {
        Schema::dropIfExists('tzc_embeddings');
    }
};
