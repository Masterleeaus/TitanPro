<?php

namespace App\Platform\Tenancy;

class TenancyRegistry
{
    /**
     * @var array<string, array{resolvers: array<int, array<string, mixed>>, policies: array<int, array<string, mixed>>}>
     */
    private array $entries = [];

    /** @var array<string, array<string, mixed>> */
    private array $resolversByKey = [];

    /** @var array<string, array<string, mixed>> */
    private array $policiesByKey = [];

    /**
     * @param  array<string, mixed>  $manifest
     */
    public function registerManifest(string $module, array $manifest): void
    {
        $this->entries[$module] = [
            'resolvers' => $this->normalizeEntries($module, (array) ($manifest['resolvers'] ?? []), 'resolver'),
            'policies' => $this->normalizeEntries($module, (array) ($manifest['policies'] ?? []), 'policy'),
        ];

        $this->rebuildIndex();
    }

    /**
     * @return array<string, array{resolvers: array<int, array<string, mixed>>, policies: array<int, array<string, mixed>>}>
     */
    public function all(): array
    {
        return $this->entries;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function resolvers(?string $module = null): array
    {
        return $this->entriesByType('resolvers', $module);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function policies(?string $module = null): array
    {
        return $this->entriesByType('policies', $module);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findResolver(string $key): ?array
    {
        return $this->resolversByKey[$key] ?? null;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findPolicy(string $key): ?array
    {
        return $this->policiesByKey[$key] ?? null;
    }

    private function rebuildIndex(): void
    {
        $this->resolversByKey = [];
        $this->policiesByKey = [];

        foreach ($this->entries as $entry) {
            foreach ($entry['resolvers'] as $resolver) {
                $this->resolversByKey[$resolver['key']] = $resolver;
            }

            foreach ($entry['policies'] as $policy) {
                $this->policiesByKey[$policy['key']] = $policy;
            }
        }
    }

    /**
     * @param  array<int|string, mixed>  $entries
     * @return array<int, array<string, mixed>>
     */
    private function normalizeEntries(string $module, array $entries, string $type): array
    {
        $normalized = [];

        foreach ($entries as $entryKey => $entry) {
            if (is_string($entry) && $entry !== '') {
                $normalized[] = [
                    'module' => $module,
                    'type' => $type,
                    'key' => $entry,
                    'class' => $entry,
                ];

                continue;
            }

            if (! is_array($entry)) {
                continue;
            }

            $key = is_string($entryKey) && $entryKey !== ''
                ? $entryKey
                : ($entry['key'] ?? $entry['id'] ?? $entry['name'] ?? $entry['slug'] ?? $entry['class'] ?? null);

            if (! is_string($key) || $key === '') {
                continue;
            }

            $normalized[] = array_merge(
                [
                    'module' => $module,
                    'type' => $type,
                    'key' => $key,
                    'class' => $entry['class'] ?? $key,
                ],
                $entry
            );
        }

        return array_values($normalized);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function entriesByType(string $type, ?string $module = null): array
    {
        if ($module !== null) {
            return $this->entries[$module][$type] ?? [];
        }

        $combined = [];
        foreach ($this->entries as $entry) {
            $combined = array_merge($combined, $entry[$type] ?? []);
        }

        return $combined;
    }
}
