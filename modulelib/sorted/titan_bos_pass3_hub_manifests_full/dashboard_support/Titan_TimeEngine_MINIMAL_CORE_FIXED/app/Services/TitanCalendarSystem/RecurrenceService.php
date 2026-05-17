<?php

namespace App\Services\TitanCalendarSystem;

use App\Models\Tz\TzCalendarEvent;
use App\Models\Tz\TzCalendarRecurrenceRule;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class RecurrenceService
{
    public function createOrUpdateForEvent(TzCalendarEvent $event, array $ruleData = []): void
    {
        if (! Schema::hasTable('tz_calendar_recurrence_rules')) {
            return;
        }

        $frequency = strtoupper((string) ($ruleData['frequency'] ?? ''));
        if (! in_array($frequency, ['DAILY', 'WEEKLY', 'MONTHLY'], true)) {
            TzCalendarRecurrenceRule::query()->where('calendar_event_id', $event->id)->delete();
            return;
        }

        $rule = TzCalendarRecurrenceRule::query()->firstOrNew(['calendar_event_id' => $event->id]);
        $rule->fill([
            'team_id' => $event->team_id,
            'company_id' => $event->company_id,
            'user_id' => $event->user_id,
            'created_by_team_id' => $event->created_by_team_id,
            'calendar_event_id' => $event->id,
            'frequency' => $frequency,
            'interval_value' => max(1, (int) ($ruleData['interval_value'] ?? 1)),
            'days_of_week' => array_values(array_filter((array) ($ruleData['days_of_week'] ?? []))),
            'until_at' => ! empty($ruleData['until_at']) ? Carbon::parse($ruleData['until_at']) : null,
            'occurrence_limit' => ! empty($ruleData['occurrence_limit']) ? (int) $ruleData['occurrence_limit'] : null,
            'is_active' => true,
        ]);
        $rule->save();
    }

    public function expand(array $events, array $filters = []): array
    {
        if (! Schema::hasTable('tz_calendar_recurrence_rules')) {
            return $events;
        }

        $start = ! empty($filters['start']) ? Carbon::parse($filters['start']) : now()->startOfMonth();
        $end = ! empty($filters['end']) ? Carbon::parse($filters['end']) : now()->endOfMonth();
        $rules = TzCalendarRecurrenceRule::query()->where('is_active', true)->get()->keyBy('calendar_event_id');

        $expanded = [];
        foreach ($events as $event) {
            $expanded[] = $event;
            $baseId = (int) data_get($event, 'extendedProps.calendar_event_id', 0);
            if (! $baseId || ! $rules->has($baseId)) {
                continue;
            }

            $expanded = array_merge($expanded, $this->expandSingle($event, $rules->get($baseId), $start, $end));
        }

        return $expanded;
    }

    protected function expandSingle(array $event, TzCalendarRecurrenceRule $rule, Carbon $rangeStart, Carbon $rangeEnd): array
    {
        $start = Carbon::parse($event['start']);
        $end = ! empty($event['end']) ? Carbon::parse($event['end']) : null;
        $duration = $end ? $end->diffInSeconds($start) : 0;
        $periodEnd = $rule->until_at ? $rule->until_at->copy()->endOfDay()->min($rangeEnd) : $rangeEnd;
        $period = CarbonPeriod::create($start->copy(), '1 day', $periodEnd);

        $items = [];
        $count = 0;
        foreach ($period as $point) {
            if ($point->lte($start)) {
                continue;
            }
            if (! $this->matchesRule($point, $start, $rule)) {
                continue;
            }

            $clone = $event;
            $clone['id'] = $event['id'] . ':r:' . $point->format('YmdHis');
            $clone['start'] = $point->copy()->setTimeFrom($start)->toIso8601String();
            $clone['end'] = $duration > 0 ? $point->copy()->setTimeFrom($start)->addSeconds($duration)->toIso8601String() : null;
            $clone['editable'] = false;
            $clone['extendedProps'] = array_merge($clone['extendedProps'] ?? [], [
                'is_recurrence_instance' => true,
                'recurrence_parent_id' => $event['id'],
                'recurrence_rule_id' => $rule->id,
            ]);
            $items[] = $clone;
            $count++;
            if ($rule->occurrence_limit && $count >= max(0, $rule->occurrence_limit - 1)) {
                break;
            }
        }

        return $items;
    }

    protected function matchesRule(Carbon $point, Carbon $seed, TzCalendarRecurrenceRule $rule): bool
    {
        $interval = max(1, (int) ($rule->interval_value ?? 1));

        return match ($rule->frequency) {
            'DAILY' => $seed->diffInDays($point) % $interval === 0,
            'WEEKLY' => $seed->diffInWeeks($point) % $interval === 0
                && (empty($rule->days_of_week) || in_array(strtoupper($point->shortEnglishDayOfWeek), array_map('strtoupper', $rule->days_of_week), true)),
            'MONTHLY' => $seed->diffInMonths($point) % $interval === 0 && $point->day === $seed->day,
            default => false,
        };
    }
}
