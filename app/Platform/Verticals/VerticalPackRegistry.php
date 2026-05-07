<?php

namespace App\Platform\Verticals;

class VerticalPackRegistry
{
    /**
     * @var array<string, array{default: string|null, packs: array<int, array<string, mixed>>}>
     */
    private array $entries = [];

    /** @var array<string, array<string, mixed>> */
    private array $packsByKey = [];

    /**
     * @param  array<string, mixed>  $manifest
     */
    public function registerManifest(string $module, array $manifest): void
    {
        $supported = $manifest['supported'] ?? $manifest['verticals'] ?? $manifest['packs'] ?? [];
        $packs = [];

        foreach ((array) $supported as $entryKey => $pack) {
            if (is_string($pack) && $pack !== '') {
                $packs[] = [
                    'module' => $module,
                    'key' => $pack,
                    'vertical' => $pack,
                    'config' => [],
                ];

                continue;
            }

            if (! is_array($pack)) {
                continue;
            }

            $key = is_string($entryKey) && $entryKey !== ''
                ? $entryKey
                : ($pack['key'] ?? $pack['id'] ?? $pack['slug'] ?? $pack['name'] ?? $pack['vertical'] ?? null);

            if (! is_string($key) || $key === '') {
                continue;
            }

            $packs[] = [
                'module' => $module,
                'key' => $key,
                'vertical' => $key,
                'config' => $pack,
            ] + $pack;
        }

        $default = $manifest['default'] ?? null;

        $this->entries[$module] = [
            'default' => is_string($default) && $default !== ''
                ? $default
                : null,
            'packs' => array_values($packs),
        ];

        $this->rebuildIndex();
    }

    /**
     * @return array<string, array{default: string|null, packs: array<int, array<string, mixed>>}>
     */
    public function all(): array
    {
        return $this->entries;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function packs(?string $module = null): array
    {
        if ($module !== null) {
            return $this->entries[$module]['packs'] ?? [];
        }

        $combined = [];
        foreach ($this->entries as $entry) {
            $combined = array_merge($combined, $entry['packs']);
        }

        return $combined;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function find(string $key): ?array
    {
        return $this->packsByKey[$key] ?? null;
    }

    public function default(?string $module = null): ?string
    {
        if ($module !== null) {
            return $this->entries[$module]['default'] ?? null;
        }

        foreach ($this->entries as $entry) {
            if (is_string($entry['default']) && $entry['default'] !== '') {
                return $entry['default'];
            }
        }

        return null;
    }

    private function rebuildIndex(): void
    {
        $this->packsByKey = [];

        foreach ($this->entries as $entry) {
            foreach ($entry['packs'] as $pack) {
                $this->packsByKey[$pack['key']] = $pack;
            }
        }
    }
}
