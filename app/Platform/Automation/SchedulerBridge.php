<?php

namespace App\Platform\Automation;

use Illuminate\Console\Scheduling\Schedule;

/**
 * SchedulerBridge wires scheduler-type automations from the AutomationRegistry
 * into the Laravel task scheduler.
 *
 * Call SchedulerBridge::bind($schedule) inside routes/console.php or a service
 * provider's boot() after the registry has been populated by all modules.
 *
 * Supported cadences (maps to Schedule method names):
 *   - everyMinute
 *   - everyFiveMinutes
 *   - everyTenMinutes
 *   - everyFifteenMinutes
 *   - everyThirtyMinutes
 *   - hourly
 *   - daily
 *   - weekly
 *   - monthly
 *   - dailyAt:HH:MM   (e.g. "dailyAt:08:00")
 *
 * Each scheduled automation is dispatched through TriggerDispatcher using the
 * automation's own id as the trigger key, keeping the run-log consistent.
 */
class SchedulerBridge
{
    public function __construct(
        private readonly AutomationRegistry $registry,
        private readonly TriggerDispatcher  $dispatcher,
    ) {}

    /**
     * Register all scheduled automations with the Laravel task scheduler.
     */
    public function bind(Schedule $schedule): void
    {
        foreach ($this->registry->scheduled() as $automation) {
            $cadence   = $automation['schedule'];
            $id        = $automation['id'];
            $companyId = $automation['company_id'] ?? null;

            $event = $schedule->call(function () use ($id, $companyId) {
                $this->dispatcher->dispatch($id, [], $companyId);
            })->name("automation:{$id}")->withoutOverlapping();

            $this->applyCadence($event, $cadence);
        }

        foreach ($this->registry->schedulerHooks() as $hook) {
            $class = $hook['class'] ?? null;
            if (! is_string($class) || $class === '' || ! class_exists($class)) {
                continue;
            }

            $id = (string) ($hook['key'] ?? $class);
            $cadence = (string) ($hook['schedule'] ?? 'daily');

            $event = $schedule->call(function () use ($class, $id, $cadence) {
                $instance = app()->make($class);
                if (method_exists($instance, 'handle')) {
                    $instance->handle([
                        'scheduler_hook' => $id,
                        'cadence' => $cadence,
                    ]);
                } else {
                    logger()->warning('Automation scheduler hook class is missing handle() method.', [
                        'class' => $class,
                        'hook' => $id,
                    ]);
                }
            })->name("automation:scheduler-hook:{$id}")->withoutOverlapping();

            $this->applyCadence($event, $cadence);
        }
    }

    /**
     * Apply the cadence string to a scheduled event instance.
     *
     * @param \Illuminate\Console\Scheduling\Event $event
     * @param string $cadence
     */
    private function applyCadence(object $event, string $cadence): void
    {
        // Support "dailyAt:08:00" syntax.
        if (str_starts_with($cadence, 'dailyAt:')) {
            $time = substr($cadence, strlen('dailyAt:'));
            $event->dailyAt($time);
            return;
        }

        $allowed = [
            'everyMinute', 'everyFiveMinutes', 'everyTenMinutes',
            'everyFifteenMinutes', 'everyThirtyMinutes',
            'hourly', 'daily', 'weekly', 'monthly',
        ];

        if (in_array($cadence, $allowed, true) && method_exists($event, $cadence)) {
            $event->{$cadence}();
        } else {
            // Fallback to daily if unrecognised cadence.
            $event->daily();
        }
    }
}
