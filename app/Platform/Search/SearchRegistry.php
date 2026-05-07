<?php

namespace App\Platform\Search;

class SearchRegistry
{
    /**
     * @var array<string, array<int, array<string, mixed>>>
     */
    private array $entries = [];

    /** @var array<string, array<string, mixed>> */
    private array $indexByKey = [];

    /**
     * @param  array<string, mixed>  $manifest
     */
    public function registerManifest(string $module, array $manifest): void
    {
        $indexes = [];

        foreach ((array) ($manifest['indexes'] ?? []) as $entryKey => $index) {
            if (is_string($index) && $index !== '') {
                $indexes[] = [
                    'module' => $module,
                    'key' => $index,
                    'index' => $index,
                ];

                continue;
            }

            if (! is_array($index)) {
                continue;
            }

            $key = is_string($entryKey) && $entryKey !== ''
                ? $entryKey
                : ($index['key'] ?? $index['id'] ?? $index['name'] ?? $index['index'] ?? $index['model'] ?? null);

            if (! is_string($key) || $key === '') {
                continue;
            }

            $indexes[] = array_merge(
                [
                    'module' => $module,
                    'key' => $key,
                    'index' => $index['index'] ?? $key,
                ],
                $index
            );
        }

        $this->entries[$module] = array_values($indexes);
        $this->rebuildIndex();
    }

    /**
     * @return array<string, array<int, array<string, mixed>>>
     */
    public function all(): array
    {
        return $this->entries;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function indexes(?string $module = null): array
    {
        if ($module !== null) {
            return $this->entries[$module] ?? [];
        }

        $combined = [];
        foreach ($this->entries as $entry) {
            $combined = array_merge($combined, $entry);
        }

        return $combined;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function find(string $key): ?array
    {
        return $this->indexByKey[$key] ?? null;
    }

    private function rebuildIndex(): void
    {
        $this->indexByKey = [];

        foreach ($this->entries as $moduleEntries) {
            foreach ($moduleEntries as $entry) {
                $this->indexByKey[$entry['key']] = $entry;
            }
        }
    }
}
