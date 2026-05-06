<?php

namespace Modules\TitanZero\Services\Retrieval;

use Modules\TitanZero\DTO\RetrievalResult;
use Modules\TitanZero\Entities\TitanZeroDocumentChunk;

/**
 * ManifestDrivenRetrievalService — tenant-scoped, manifest-backed retrieval runtime.
 *
 * Reads a retrieval policy (loaded from the module's `AI/Retrieval/retrieval.policy.json`
 * or passed directly as an array) to determine the retrieval strategy and limits.
 *
 * Responsibilities:
 * - Parse the policy's `top_k` / `strategy` settings.
 * - Enforce tenant boundary via `company_id` — cross-tenant leakage is structurally prevented.
 * - Return a typed `RetrievalResult[]` collection (never throws on an empty index).
 */
class ManifestDrivenRetrievalService
{
    /** Default number of results when the policy omits `top_k`. */
    private const DEFAULT_TOP_K = 5;

    /** Divisor applied to a document's `preferred_weight` to compute a score bonus. */
    private const PREFERENCE_WEIGHT_DIVISOR = 20;

    /** Maximum score bonus that can come from document preference weighting. */
    private const MAX_PREFERENCE_BOOST = 5;

    /**
     * Over-fetch multiplier: we retrieve this many times more candidates than
     * `top_k` so we can score and re-rank them before trimming to the final limit.
     */
    private const OVERFETCH_MULTIPLIER = 3;

    /**
     * Retrieve chunks that are relevant to $query, scoped to the given tenant.
     *
     * @param  array       $policy      Decoded contents of `retrieval.policy.json`.
     * @param  string      $query       The free-text search query.
     * @param  int|null    $company_id  Tenant identifier.  When provided every result
     *                                  is guaranteed to belong to this tenant.
     *                                  Pass null only for platform-level (non-tenant) searches.
     * @param  int|null    $limit       Override for top_k; falls back to policy value then default.
     * @return RetrievalResult[]
     */
    public function retrieve(
        array   $policy,
        string  $query,
        ?int    $company_id = null,
        ?int    $limit      = null,
    ): array {
        $topK = $limit ?? (int) ($policy['top_k'] ?? self::DEFAULT_TOP_K);
        if ($topK < 1) {
            $topK = self::DEFAULT_TOP_K;
        }

        $q = trim($query);
        if ($q === '') {
            return [];
        }

        $terms = preg_split('/\s+/', mb_strtolower($q));
        $terms = array_values(array_filter(array_unique($terms), fn ($t) => mb_strlen($t) >= 3));

        if (empty($terms)) {
            return [];
        }

        // Build the base query — enforce tenant boundary first.
        $builder = TitanZeroDocumentChunk::query()->with('document');

        if ($company_id !== null) {
            // Scope to the tenant's documents.  The join is on the document's company_id
            // so that a chunk can never leak across tenant boundaries.
            $builder->whereHas('document', function ($q) use ($company_id) {
                $q->where('company_id', $company_id);
            });
        }

        // Full-text keyword filter across chunk content.
        $builder->where(function ($w) use ($terms) {
            foreach ($terms as $term) {
                $w->orWhereRaw('LOWER(content) LIKE ?', ['%' . $term . '%']);
            }
        });

        // Over-fetch so we can score and trim to topK.
        $chunks = $builder->orderByDesc('id')->limit($topK * self::OVERFETCH_MULTIPLIER)->get();

        if ($chunks->isEmpty()) {
            return [];
        }

        // Score each chunk by term hit-count + document preference weight.
        $scored = [];
        foreach ($chunks as $chunk) {
            $text  = mb_strtolower($chunk->content);
            $score = 0;
            foreach ($terms as $term) {
                if (str_contains($text, $term)) {
                    $score++;
                }
            }
            $pref  = (int) ($chunk->document?->preferred_weight ?? 0);
            $score += ($pref > 0) ? min(self::MAX_PREFERENCE_BOOST, intdiv($pref, self::PREFERENCE_WEIGHT_DIVISOR)) : 0;

            if ($score > 0) {
                $scored[] = ['chunk' => $chunk, 'score' => $score];
            }
        }

        usort($scored, fn ($a, $b) => $b['score'] <=> $a['score']);

        $results = [];
        foreach (array_slice($scored, 0, $topK) as $row) {
            $chunk    = $row['chunk'];
            $doc      = $chunk->document;
            $results[] = new RetrievalResult(
                chunk_id:    "{$chunk->document_id}:{$chunk->chunk_index}",
                document_id: $chunk->document_id,
                chunk_index: $chunk->chunk_index,
                text:        $chunk->content,
                metadata:    [
                    'title'           => $doc?->title,
                    'doc_type'        => $doc?->doc_type,
                    'authority_level' => $doc?->authority_level,
                    'jurisdiction'    => $doc?->jurisdiction,
                    'is_superseded'   => (bool) ($doc?->is_superseded),
                    'preferred_weight'=> (int)  ($doc?->preferred_weight ?? 0),
                    'company_id'      => $doc?->company_id,
                ],
                score:       (float) $row['score'],
            );
        }

        return $results;
    }

    /**
     * Load a retrieval policy from a JSON file on disk.
     *
     * @param  string  $path  Absolute path to `retrieval.policy.json`.
     * @return array          Decoded policy, or an empty array when the file is missing or malformed.
     */
    public static function loadPolicy(string $path): array
    {
        if (!file_exists($path)) {
            return [];
        }

        $decoded = json_decode(file_get_contents($path), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            \Illuminate\Support\Facades\Log::warning('[ManifestDrivenRetrievalService] Failed to parse policy JSON', [
                'path'  => $path,
                'error' => json_last_error_msg(),
            ]);
            return [];
        }

        return is_array($decoded) ? $decoded : [];
    }
}
