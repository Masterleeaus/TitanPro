<?php

namespace Modules\TitanZero\DTO;

/**
 * RetrievalResult — value object representing a single retrieved chunk.
 *
 * Returned by ManifestDrivenRetrievalService. Each result carries the
 * chunk text, its composite identifier, and document-level metadata.
 */
class RetrievalResult
{
    /**
     * @param  string  $chunk_id    Composite identifier "{document_id}:{chunk_index}"
     * @param  int     $document_id Primary key of the source document
     * @param  int     $chunk_index Zero-based position of the chunk within its document
     * @param  string  $text        The raw chunk content
     * @param  array   $metadata    Document-level metadata (title, doc_type, authority_level, …)
     * @param  float   $score       Relevance score produced by the retrieval strategy
     */
    public function __construct(
        public readonly string $chunk_id,
        public readonly int    $document_id,
        public readonly int    $chunk_index,
        public readonly string $text,
        public readonly array  $metadata,
        public readonly float  $score,
    ) {}
}
