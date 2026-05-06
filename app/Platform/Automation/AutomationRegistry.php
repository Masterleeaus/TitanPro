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
}
