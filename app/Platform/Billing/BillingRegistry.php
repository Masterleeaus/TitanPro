<?php

namespace App\Platform\Billing;

class BillingRegistry
{
    /**
     * @var array<string, array{plans: array<int, array<string, mixed>>, meters: array<int, array<string, mixed>>, limits: array<int, array<string, mixed>>}>
     */
    private array $entries = [];

    /** @var array<string, array<string, mixed>> */
    private array $plansByKey = [];

    /** @var array<string, array<string, mixed>> */
    private array $metersByKey = [];

    /** @var array<string, array<string, mixed>> */
    private array $limitsByKey = [];

    /**
     * @param  array<string, mixed>  $manifest
     */
    public function registerManifest(string $module, array $manifest): void
    {
        $this->entries[$module] = [
            'plans' => $this->normalizeEntries($module, (array) ($manifest['plans'] ?? []), 'plan'),
            'meters' => $this->normalizeEntries($module, (array) ($manifest['meters'] ?? []), 'meter'),
            'limits' => $this->normalizeEntries($module, (array) ($manifest['limits'] ?? []), 'limit'),
        ];

        $this->rebuildIndex();
    }

    /**
     * @return array<string, array{plans: array<int, array<string, mixed>>, meters: array<int, array<string, mixed>>, limits: array<int, array<string, mixed>>}>
     */
    public function all(): array
    {
        return $this->entries;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function plans(?string $module = null): array
    {
        return $this->entriesByType('plans', $module);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function meters(?string $module = null): array
    {
        return $this->entriesByType('meters', $module);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function limits(?string $module = null): array
    {
        return $this->entriesByType('limits', $module);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findPlan(string $key): ?array
    {
        return $this->plansByKey[$key] ?? null;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findMeter(string $key): ?array
    {
        return $this->metersByKey[$key] ?? null;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findLimit(string $key): ?array
    {
        return $this->limitsByKey[$key] ?? null;
    }

    private function rebuildIndex(): void
    {
        $this->plansByKey = [];
        $this->metersByKey = [];
        $this->limitsByKey = [];

        foreach ($this->entries as $entry) {
            foreach ($entry['plans'] as $plan) {
                $this->plansByKey[$plan['key']] = $plan;
            }

            foreach ($entry['meters'] as $meter) {
                $this->metersByKey[$meter['key']] = $meter;
            }

            foreach ($entry['limits'] as $limit) {
                $this->limitsByKey[$limit['key']] = $limit;
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

            if (is_array($entry)) {
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
                    ],
                    $entry
                );

                continue;
            }

            if (! is_string($entryKey) || $entryKey === '') {
                continue;
            }

            $normalized[] = [
                'module' => $module,
                'type' => $type,
                'key' => $entryKey,
                'value' => $entry,
            ];
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
