<?php

namespace Modules\TitanEchoAssist\Services;

use Illuminate\Support\Collection;
use Modules\TitanEchoAssist\Models\ChatbotEmbedding;
use Modules\TitanEchoAssist\Services\Embedders\Contracts\EmbedderInterface;

class KnowledgeRetriever
{
    public function __construct(private readonly EmbedderInterface $embedder) {}

    /**
     * @return array<int, array{content: string, similarity: float}>
     */
    public function retrieve(string $query, string $chatbotId, int $topK = 5): array
    {
        $query = trim($query);

        if ($query === '' || trim($chatbotId) === '') {
            return [];
        }

        $queryEmbedding = $this->embedder->embed($query);
        if ($queryEmbedding === []) {
            return [];
        }

        /** @var Collection<int, ChatbotEmbedding> $rows */
        $rows = ChatbotEmbedding::query()
            ->where('chatbot_id', $chatbotId)
            ->whereNotNull('embedding')
            ->get(['content', 'embedding']);

        $scored = [];

        foreach ($rows as $row) {
            $content = trim((string) $row->content);
            $embedding = is_array($row->embedding) ? $row->embedding : [];

            if ($content === '' || $embedding === []) {
                continue;
            }

            $scored[] = [
                'content' => $content,
                'similarity' => $this->cosineSimilarity($queryEmbedding, $embedding),
            ];
        }

        usort($scored, static fn (array $a, array $b): int => $b['similarity'] <=> $a['similarity']);

        return array_slice($scored, 0, max(1, $topK));
    }

    /**
     * @param  array<int, float|int> $left
     * @param  array<int, float|int> $right
     */
    private function cosineSimilarity(array $left, array $right): float
    {
        $length = min(count($left), count($right));
        if ($length === 0) {
            return 0.0;
        }

        $dot = 0.0;
        $leftMagnitude = 0.0;
        $rightMagnitude = 0.0;

        for ($index = 0; $index < $length; $index++) {
            $a = (float) $left[$index];
            $b = (float) $right[$index];

            $dot += $a * $b;
            $leftMagnitude += $a * $a;
            $rightMagnitude += $b * $b;
        }

        if ($leftMagnitude === 0.0 || $rightMagnitude === 0.0) {
            return 0.0;
        }

        return $dot / (sqrt($leftMagnitude) * sqrt($rightMagnitude));
    }
}
