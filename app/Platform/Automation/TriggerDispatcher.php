<?php

namespace App\Platform\Automation;

use App\Jobs\ExecuteAutomationJob;
use App\Models\AutomationRun;

/**
 * TriggerDispatcher routes a named trigger (or a Laravel event class name) to all
 * automations registered for that trigger in the AutomationRegistry.
 *
 * Usage — from a listener or anywhere in the application:
 *
 *   app(TriggerDispatcher::class)->dispatch('crmcore.deal.won', $payload, $companyId);
 *
 * The dispatcher creates an AutomationRun record for each matching automation and
 * pushes an ExecuteAutomationJob onto the queue.
 */
class TriggerDispatcher
{
    public function __construct(private readonly AutomationRegistry $registry) {}

    /**
     * Dispatch all automations registered for the given trigger key.
     *
     * @param string     $triggerKey  e.g. "crmcore.deal.won" or an event class FQCN
     * @param array      $payload     arbitrary context data passed to the handler
     * @param int|null   $companyId   tenant scope (null = platform-wide)
     */
    public function dispatch(string $triggerKey, array $payload = [], ?int $companyId = null): void
    {
        $automations = $this->registry->forTrigger($triggerKey);

        foreach ($automations as $automation) {
            $run = AutomationRun::create([
                'company_id'    => $companyId ?? $automation['company_id'] ?? null,
                'automation_id' => $automation['id'],
                'trigger'       => $triggerKey,
                'handler'       => $automation['handler'] ?? null,
                'status'        => AutomationRun::STATUS_QUEUED,
                'attempts'      => 0,
                'max_attempts'  => $automation['retries'] ?? 3,
                'payload'       => $payload,
            ]);

            ExecuteAutomationJob::dispatch($run->id, $automation);
        }
    }

    /**
     * Convenience helper: dispatch using a Laravel event object.
     * The trigger key will be the event's class name.
     */
    public function dispatchEvent(object $event, array $payload = [], ?int $companyId = null): void
    {
        $this->dispatch(get_class($event), $payload, $companyId);
    }
}
