<?php

namespace App\Platform\Automation;

/**
 * Central registry for automation definitions.
 *
 * Modules call AutomationRegistry::register() inside their AutomationServiceProvider
 * to declare which trigger/handler pairs they expose.
 *
 * Each automation definition array supports:
 *   - id          (string, required)   unique automation identifier, e.g. "crm.deal_won"
 *   - trigger     (string, required)   trigger key, e.g. "crmcore.deal.won" or an event FQCN
 *   - handler     (string, required)   handler class FQCN — must expose handle(array $payload): mixed
 *   - pipeline    (string|null)        optional pipeline class FQCN
 *   - schedule    (string|null)        scheduler cadence: "daily", "hourly", "everyFiveMinutes", …
 *   - retries     (int)                max attempts before the run is marked failed (default 3)
 *   - retry_after (int)                seconds between retries using exponential back-off (default 60)
 *   - company_id  (int|null)           tenant scope (null = platform-wide)
 */
class AutomationRegistry
{
    /** @var array<string, array> Keyed by automation id */
    private array $automations = [];

    /**
     * Manifest-derived automation declarations keyed by module name.
     *
     * @var array<string, array{triggers: array<int, array<string, mixed>>, handlers: array<int, array<string, mixed>>, pipelines: array<int, array<string, mixed>>, schedulers: array<int, array<string, mixed>>}>
     */
    private array $manifestEntries = [];

    /**
     * Register one or more automation definitions.
     *
     * @param array<string, mixed> $definition
     */
    public function register(array $definition): void
    {
        $id = $definition['id'] ?? null;

        if (! $id) {
            return;
        }

        $this->automations[$id] = array_merge([
            'retries'     => 3,
            'retry_after' => 60,
            'pipeline'    => null,
            'schedule'    => null,
            'company_id'  => null,
        ], $definition);
    }

    /**
     * Return every registered automation.
     *
     * @return array<string, array>
     */
    public function all(): array
    {
        return $this->automations;
    }

    /**
     * Return all automations whose trigger matches the given key.
     *
     * @return array<int, array>
     */
    public function forTrigger(string $triggerKey): array
    {
        return array_values(
            array_filter(
                $this->automations,
                fn (array $a) => ($a['trigger'] ?? '') === $triggerKey,
            )
        );
    }

    /**
     * Return all automations that carry a schedule cadence.
     *
     * @return array<int, array>
     */
    public function scheduled(): array
    {
        return array_values(
            array_filter(
                $this->automations,
                fn (array $a) => ! empty($a['schedule']),
            )
        );
    }

    /**
     * Retrieve a single automation definition by id, or null.
     */
    public function find(string $id): ?array
    {
        return $this->automations[$id] ?? null;
    }

    /**
     * Register manifest-declared automation surfaces for a single module.
     *
     * Re-registering the same module replaces existing entries, making the
     * operation idempotent.
     *
     * @param  array<string, mixed>  $manifest
     */
    public function registerManifest(string $module, array $manifest): void
    {
        $this->manifestEntries[$module] = [
            'triggers' => $this->normalizeManifestEntries($module, (array) ($manifest['triggers'] ?? []), 'trigger'),
            'handlers' => $this->normalizeManifestEntries($module, (array) ($manifest['handlers'] ?? []), 'handler'),
            'pipelines' => $this->normalizeManifestEntries($module, (array) ($manifest['pipelines'] ?? []), 'pipeline'),
            'schedulers' => $this->normalizeManifestEntries($module, (array) ($manifest['schedulers'] ?? []), 'scheduler'),
        ];
    }

    /**
     * @return array<string, array{triggers: array<int, array<string, mixed>>, handlers: array<int, array<string, mixed>>, pipelines: array<int, array<string, mixed>>, schedulers: array<int, array<string, mixed>>}>
     */
    public function manifestEntries(): array
    {
        return $this->manifestEntries;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function triggers(?string $module = null): array
    {
        return $this->manifestEntriesByType('triggers', $module);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function handlers(?string $module = null): array
    {
        return $this->manifestEntriesByType('handlers', $module);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function pipelines(?string $module = null): array
    {
        return $this->manifestEntriesByType('pipelines', $module);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function schedulerHooks(?string $module = null): array
    {
        return $this->manifestEntriesByType('schedulers', $module);
    }

    /**
     * @param  array<int, mixed>  $entries
     * @return array<int, array<string, mixed>>
     */
    private function normalizeManifestEntries(string $module, array $entries, string $type): array
    {
        $normalized = [];

        foreach ($entries as $entry) {
            if (is_string($entry)) {
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

            $key = $entry['key'] ?? $entry['id'] ?? $entry['name'] ?? $entry['class'] ?? null;

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

        return $normalized;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function manifestEntriesByType(string $type, ?string $module = null): array
    {
        if ($module !== null) {
            return $this->manifestEntries[$module][$type] ?? [];
        }

        $combined = [];
        foreach ($this->manifestEntries as $entries) {
            $combined = array_merge($combined, $entries[$type] ?? []);
        }

        return $combined;
    }
}
