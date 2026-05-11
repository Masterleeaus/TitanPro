<?php

namespace App\Services\TitanCalendarSystem;

use App\Models\Tz\TzCalendarSyncAccount;
use App\Services\TitanCalendarSystem\Support\CalendarEventNormalizer;
use Illuminate\Support\Facades\Schema;

class GoogleCalendarService
{
    public function summary(): array
    {
        return [
            'driver' => class_exists(\Spatie\GoogleCalendar\Event::class) ? 'spatie/laravel-google-calendar' : 'scaffold_only',
            'status' => class_exists(\Spatie\GoogleCalendar\Event::class) ? 'available' : 'install_required',
            'sync_accounts_ready' => Schema::hasTable('tz_calendar_sync_accounts'),
            'sync_links_ready' => Schema::hasTable('tz_calendar_sync_links'),
            'supports_pull' => true,
            'supports_push' => true,
        ];
    }

    public function pullEvents(array $filters = []): array
    {
        if (! Schema::hasTable('tz_calendar_sync_accounts')) {
            return [];
        }

        return TzCalendarSyncAccount::query()
            ->where('provider', 'google')
            ->where('is_active', true)
            ->limit(5)
            ->get()
            ->flatMap(function (TzCalendarSyncAccount $account) use ($filters): array {
                $events = data_get($account->token_json, 'mock_events', []);

                return collect($events)->map(fn (array $event): array => CalendarEventNormalizer::normalize([
                    'id' => 'google:' . ($event['id'] ?? uniqid()),
                    'title' => $event['title'] ?? ($account->account_name . ' Google Event'),
                    'start' => $event['start'] ?? now()->toIso8601String(),
                    'end' => $event['end'] ?? null,
                    'source' => 'google',
                    'status' => $event['status'] ?? 'external',
                    'event_type' => $event['event_type'] ?? 'external_calendar',
                    'color' => '#0ea5e9',
                    'editable' => false,
                    'meta' => [
                        'sync_account_id' => $account->id,
                        'calendar_id' => $account->calendar_id,
                    ],
                ]))->all();
            })->values()->all();
    }
}
