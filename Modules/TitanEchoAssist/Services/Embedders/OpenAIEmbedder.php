<?php

namespace Modules\TitanEchoAssist\Services\Embedders;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\TitanEchoAssist\Services\Embedders\Contracts\EmbedderInterface;

class OpenAIEmbedder implements EmbedderInterface
{
    private const MODEL = 'text-embedding-ada-002';
    private const DIMENSIONS = 1536;

    public function embed(string $text): array
    {
        return $this->embedBatch([$text])[0] ?? $this->zeroVector();
    }

    public function embedBatch(array $texts): array
    {
        $cleaned = array_values(array_map(
            static fn (mixed $text): string => trim((string) $text),
            array_filter($texts, static fn (mixed $text): bool => trim((string) $text) !== ''),
        ));

        if ($cleaned === []) {
            return [];
        }

        $apiKey = (string) config('titan-chatbot.ai.openai_api_key', (string) getenv('OPENAI_API_KEY'));
        if ($apiKey === '') {
            return array_fill(0, count($cleaned), $this->zeroVector());
        }

        try {
            $response = Http::withToken($apiKey)
                ->timeout(20)
                ->post('https://api.openai.com/v1/embeddings', [
                    'model' => self::MODEL,
                    'input' => $cleaned,
                ]);

            if ($response->failed()) {
                Log::warning('OpenAIEmbedder: request failed.', ['status' => $response->status()]);

                return array_fill(0, count($cleaned), $this->zeroVector());
            }

            $byIndex = [];
            foreach ((array) $response->json('data', []) as $row) {
                $index = (int) ($row['index'] ?? 0);
                $byIndex[$index] = $this->normalizeVector((array) ($row['embedding'] ?? []));
            }

            $vectors = [];
            for ($index = 0; $index < count($cleaned); $index++) {
                $vectors[] = $byIndex[$index] ?? $this->zeroVector();
            }

            return $vectors;
        } catch (\Throwable $e) {
            Log::warning('OpenAIEmbedder: failed to embed text.', ['error' => $e->getMessage()]);

            return array_fill(0, count($cleaned), $this->zeroVector());
        }
    }

    /**
     * @param  array<int, mixed> $values
     * @return array<int, float>
     */
    private function normalizeVector(array $values): array
    {
        $vector = array_map(static fn (mixed $value): float => (float) $value, $values);
        $vector = array_slice($vector, 0, self::DIMENSIONS);

        if (count($vector) < self::DIMENSIONS) {
            $vector = array_pad($vector, self::DIMENSIONS, 0.0);
        }

        return $vector;
    }

    /**
     * @return array<int, float>
     */
    private function zeroVector(): array
    {
        return array_fill(0, self::DIMENSIONS, 0.0);
    }
}
