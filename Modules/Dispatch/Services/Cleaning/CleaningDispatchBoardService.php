<?php

declare(strict_types=1);

namespace Modules\Dispatch\Services\Cleaning;

use Illuminate\Support\Carbon;
use Modules\Dispatch\Models\DispatchAppointment;
use Modules\Dispatch\Support\DTOs\CleaningBoardCard;
use Modules\Dispatch\Support\Enums\CleaningVisitStatus;

class CleaningDispatchBoardService
{
    public function cardsForDate(Carbon|string|null $date = null, ?int $companyId = null): array
    {
        $day = $date instanceof Carbon ? $date : Carbon::parse($date ?? 'today');

        return DispatchAppointment::query()
            ->with(['workOrder', 'technician'])
            ->when($companyId, fn ($query) => $query->where('company_id', $companyId))
            ->whereDate('starts_at', $day->toDateString())
            ->orderBy('starts_at')
            ->get()
            ->map(fn (DispatchAppointment $appointment): array => $this->cardFromAppointment($appointment)->toArray())
            ->all();
    }

    public function lanesForDate(Carbon|string|null $date = null, ?int $companyId = null): array
    {
        return collect($this->cardsForDate($date, $companyId))
            ->groupBy(fn (array $card): string => (string) ($card['cleanerName'] ?: 'Unassigned'))
            ->map(fn ($cards, string $lane): array => [
                'lane' => $lane,
                'count' => $cards->count(),
                'minutes' => $cards->sum(fn (array $card): int => $this->durationMinutes($card)),
                'cards' => $cards->values()->all(),
            ])
            ->values()
            ->all();
    }

    private function cardFromAppointment(DispatchAppointment $appointment): CleaningBoardCard
    {
        $starts = $appointment->starts_at;
        $ends = $appointment->ends_at;
        $status = (string) $appointment->status;

        return new CleaningBoardCard(
            appointmentId: (int) $appointment->getKey(),
            workOrderId: $appointment->work_order_id ? (int) $appointment->work_order_id : null,
            cleanerId: $appointment->technician_id ? (int) $appointment->technician_id : null,
            cleanerName: $appointment->technician?->name ?? $appointment->technician?->display_name ?? null,
            title: $appointment->workOrder?->title ?? 'Cleaning visit',
            status: $status,
            startsAt: $starts?->toIso8601String(),
            endsAt: $ends?->toIso8601String(),
            location: $appointment->location ?? $appointment->workOrder?->location,
            isLate: $starts && now()->greaterThan($starts) && ! in_array($status, [CleaningVisitStatus::Completed->value, CleaningVisitStatus::Cancelled->value], true),
            metadata: $appointment->metadata ?? [],
        );
    }

    private function durationMinutes(array $card): int
    {
        if (! $card['startsAt'] || ! $card['endsAt']) {
            return 0;
        }

        return max(0, Carbon::parse($card['startsAt'])->diffInMinutes(Carbon::parse($card['endsAt'])));
    }
}
