<?php

namespace Modules\TitanEchoAssist\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Modules\TitanEchoAssist\Services\Embedders\Contracts\EmbedderInterface;

class TrainingPipeline
{
    public function __construct(private readonly ?EmbedderInterface $embedder = null) {}

    /**
     * Ingest content into chatbot embeddings.
     *
     * @param  int    $chatbotId
     * @param  string $sourceType  text|qa|file|website
     * @param  string $content
     * @param  array  $metadata    Optionally: title, source_url, engine
     * @return int    Number of chunks created
     */
    public function ingest(int $chatbotId, string $sourceType, string $content, array $metadata = []): int
    {
        if (! Schema::hasTable('ext_chatbot_embeddings')) {
            return 0;
        }

        $normalizedType = $this->normalizeSourceType($sourceType);
        $chunks = match ($normalizedType) {
            'qa'      => $this->chunkQaForEmbeddings($content),
            'website' => $this->chunkWebsite($content),
            'file'    => $this->chunkFile($content, $metadata),
            default   => array_map(fn (string $chunk) => ['content' => $chunk], $this->chunkText($content)),
        };

        if (empty($chunks)) {
            return 0;
        }

        $engine    = $metadata['engine']     ?? 'default';
        $title     = $metadata['title']      ?? null;
        $sourceUrl = $metadata['source_url'] ?? null;
        $now       = now();

        foreach ($chunks as $chunkData) {
            $chunkText = trim((string) ($chunkData['content'] ?? ''));
            if ($chunkText === '') {
                continue;
            }

            DB::table('ext_chatbot_embeddings')->insert([
                'chatbot_id' => $chatbotId,
                'engine'     => $engine,
                'title'      => $title,
                'file'       => $metadata['file'] ?? $metadata['file_name'] ?? null,
                'url'        => $sourceUrl,
                'content'    => $chunkText,
                'embedding'  => $this->resolveEmbedder()->embed($chunkText),
                'type'       => $normalizedType,
                'trained_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        return count($chunks);
    }

    /**
     * Split plain text into overlapping chunks.
     *
     * @param  string $text
     * @param  int    $chunkSize  Target word count per chunk
     * @return array<string>
     */
    public function chunkText(string $text, int $chunkSize = 500): array
    {
        $text = trim($text);

        if ($text === '') {
            return [];
        }

        $words  = preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        $total  = count($words);
        $chunks = [];
        $step   = (int) ($chunkSize * 0.8); // 20 % overlap

        for ($i = 0; $i < $total; $i += $step) {
            $slice = array_slice($words, $i, $chunkSize);
            $chunks[] = implode(' ', $slice);

            if ($i + $chunkSize >= $total) {
                break;
            }
        }

        return $chunks;
    }

    /**
     * Parse Q: / A: formatted content into individual Q+A chunks.
     *
     * @param  string $content
     * @return array<string>
     */
    public function chunkQa(string $content): array
    {
        $chunks = [];
        $lines  = preg_split('/\r?\n/', $content);

        $currentQ = null;
        $currentA = [];

        foreach ($lines as $line) {
            $line = trim($line);

            if (preg_match('/^Q:\s*(.*)/i', $line, $m)) {
                if ($currentQ !== null && ! empty($currentA)) {
                    $chunks[] = 'Q: ' . $currentQ . "\nA: " . implode(' ', $currentA);
                }
                $currentQ = trim($m[1]);
                $currentA = [];
                continue;
            }

            if (preg_match('/^A:\s*(.*)/i', $line, $m)) {
                $currentA[] = trim($m[1]);
                continue;
            }

            // Continuation of an answer
            if ($currentQ !== null && $line !== '') {
                $currentA[] = $line;
            }
        }

        if ($currentQ !== null && ! empty($currentA)) {
            $chunks[] = 'Q: ' . $currentQ . "\nA: " . implode(' ', $currentA);
        }

        return array_filter($chunks);
    }

    /**
     * @return array<int, array{content: string}>
     */
    private function chunkQaForEmbeddings(string $content): array
    {
        $rows = [];

        foreach ($this->chunkQa($content) as $chunk) {
            if (preg_match('/^Q:\s*(.*?)\s*\nA:\s*(.*)$/is', $chunk, $matches)) {
                $question = trim($matches[1] ?? '');
                $answer = trim($matches[2] ?? '');

                if ($question !== '') {
                    $rows[] = ['content' => 'Q: ' . $question];
                }

                if ($answer !== '') {
                    $rows[] = ['content' => 'A: ' . $answer];
                }

                continue;
            }

            $rows[] = ['content' => $chunk];
        }

        return $rows;
    }

    /**
     * @return array<int, array{content: string}>
     */
    private function chunkWebsite(string $url): array
    {
        $url = trim($url);

        if ($url === '') {
            return [];
        }

        try {
            $response = Http::timeout(15)->get($url);
            if ($response->failed()) {
                return [];
            }

            $text = $this->htmlToText((string) $response->body());

            return array_map(fn (string $chunk) => ['content' => $chunk], $this->chunkText($text));
        } catch (\Throwable $e) {
            Log::warning('TrainingPipeline: website ingestion failed.', ['error' => $e->getMessage()]);

            return [];
        }
    }

    /**
     * @return array<int, array{content: string}>
     */
    private function chunkFile(string $content, array $metadata): array
    {
        $path = $metadata['file_path'] ?? $content;

        if (! is_string($path) || trim($path) === '') {
            return [];
        }

        $text = $this->parseFileToText($path);

        if ($text === '') {
            return [];
        }

        return array_map(fn (string $chunk) => ['content' => $chunk], $this->chunkText($text));
    }

    private function parseFileToText(string $path): string
    {
        if (! is_file($path)) {
            return '';
        }

        $extension = strtolower((string) pathinfo($path, PATHINFO_EXTENSION));

        return match ($extension) {
            'txt', 'csv', 'json', 'md' => trim((string) file_get_contents($path)),
            'docx', 'xlsx', 'xls', 'ods' => $this->extractZipXmlText($path),
            'pdf' => $this->extractPdfText($path),
            default => trim((string) file_get_contents($path)),
        };
    }

    private function extractZipXmlText(string $path): string
    {
        if (! class_exists(\ZipArchive::class)) {
            return '';
        }

        $zip = new \ZipArchive();
        if ($zip->open($path) !== true) {
            return '';
        }

        $buffer = '';
        for ($index = 0; $index < $zip->numFiles; $index++) {
            $name = (string) $zip->getNameIndex($index);
            if (! str_ends_with(strtolower($name), '.xml')) {
                continue;
            }

            $content = $zip->getFromIndex($index);
            if ($content !== false) {
                $buffer .= ' ' . strip_tags((string) $content);
            }
        }

        $zip->close();

        return trim(preg_replace('/\s+/', ' ', html_entity_decode($buffer, ENT_QUOTES | ENT_HTML5)));
    }

    private function extractPdfText(string $path): string
    {
        $binary = @file_get_contents($path);

        if ($binary === false) {
            return '';
        }

        $text = preg_replace('/[^\PC\s]/u', ' ', (string) $binary);
        $text = preg_replace('/\s+/', ' ', $text ?? '');

        return trim((string) $text);
    }

    private function htmlToText(string $html): string
    {
        $text = preg_replace('/<(script|style)\b[^>]*>.*?<\/\1>/is', ' ', $html);
        $text = strip_tags((string) $text);

        return trim((string) preg_replace('/\s+/', ' ', html_entity_decode($text, ENT_QUOTES | ENT_HTML5)));
    }

    private function normalizeSourceType(string $sourceType): string
    {
        return match (strtolower(trim($sourceType))) {
            'url' => 'website',
            'pdf' => 'file',
            default => strtolower(trim($sourceType)),
        };
    }

    private function resolveEmbedder(): EmbedderInterface
    {
        if ($this->embedder instanceof EmbedderInterface) {
            return $this->embedder;
        }

        return app(\Modules\TitanEchoAssist\Services\Embedders\OpenAIEmbedder::class);
    }
}
