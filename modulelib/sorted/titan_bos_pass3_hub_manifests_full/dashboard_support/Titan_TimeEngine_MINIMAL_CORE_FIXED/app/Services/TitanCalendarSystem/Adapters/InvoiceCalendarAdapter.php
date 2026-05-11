<?php

namespace App\Services\TitanCalendarSystem\Adapters;

use App\Services\TitanCalendarSystem\Contracts\CalendarSourceAdapterInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InvoiceCalendarAdapter implements CalendarSourceAdapterInterface
{
    public function sourceKey(): string
    {
        return 'invoice';
    }

    public function enabledForTeam(?int $teamId = null): bool
    {
        return Schema::hasTable('tz_invoices');
    }

    public function events(array $filters = []): array
    {
        $query = DB::table('tz_invoices')
            ->selectRaw("id, CONCAT('Invoice ', invoice_number) as title_text, due_date as event_start, status as status_text");

        if (! empty($filters['team_id'])) {
            $query->where('team_id', $filters['team_id']);
        }
        if (! empty($filters['start'])) {
            $query->where('due_date', '>=', $filters['start']);
        }
        if (! empty($filters['end'])) {
            $query->where('due_date', '<=', $filters['end']);
        }

        return $query->orderBy('due_date')->limit(500)->get()->map(function ($row): array {
            return [
                'id' => 'invoice-' . $row->id,
                'title' => mb_strimwidth((string) ($row->title_text ?? ''), 0, 60, '…'),
                'start' => $row->event_start ? Carbon::parse($row->event_start)->startOfDay()->toIso8601String() : null,
                'end' => null,
                'allDay' => true,
                'color' => '#b45309',
                'source' => 'invoice',
                'type' => 'invoice',
                'status' => $row->status_text ?: 'open',
                'url' => route('dashboard.user.calendar.index'),
                'meta' => ['source_table' => 'tz_invoices', 'row_id' => $row->id],
            ];
        })->filter(fn (array $event): bool => ! empty($event['start']))->values()->all();
    }
}
