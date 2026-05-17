<?php

namespace Modules\Security\Services\Reporting;

use Illuminate\Support\Carbon;
use Modules\Security\Contracts\Services\CleanerReportingServiceInterface;
use Modules\Security\Entities\Cleaner;
use Modules\Security\Entities\CleanerSite;
use Modules\Security\Entities\CleanerSiteLog;

class CleanerReportingService implements CleanerReportingServiceInterface
{
    public function daily(array $filters = []): array
    {
        $date = Carbon::parse($filters['date'] ?? now())->toDateString();

        $logs = CleanerSiteLog::query()
            ->with(['cleaner:id,name,cleaner_code,status', 'site:id,name,site_code'])
            ->whereDate('checked_in_at', $date)
            ->when($filters['site_id'] ?? null, fn ($query, $siteId) => $query->where('site_id', $siteId))
            ->orderBy('checked_in_at')
            ->get();

        return [
            'date' => $date,
            'total_check_ins' => $logs->count(),
            'open_sessions' => $logs->whereNull('checked_out_at')->count(),
            'closed_sessions' => $logs->whereNotNull('checked_out_at')->count(),
            'logs' => $logs->map(fn ($log) => [
                'id' => $log->id,
                'cleaner' => optional($log->cleaner)->name,
                'cleaner_code' => optional($log->cleaner)->cleaner_code,
                'site' => optional($log->site)->name ?: $log->site_name,
                'checkpoint' => $log->checkpoint,
                'checked_in_at' => optional($log->checked_in_at)->toIso8601String(),
                'checked_out_at' => optional($log->checked_out_at)->toIso8601String(),
                'status' => $log->status,
            ])->values()->all(),
        ];
    }

    public function siteSummary(array $filters = []): array
    {
        return CleanerSite::query()
            ->withCount([
                'cleaners',
                'siteLogs as open_sessions_count' => fn ($query) => $query->whereNull('checked_out_at'),
                'siteLogs as today_checkins_count' => fn ($query) => $query->whereDate('checked_in_at', now()->toDateString()),
            ])
            ->when($filters['active'] ?? null, fn ($query, $active) => $query->where('active', filter_var($active, FILTER_VALIDATE_BOOL)))
            ->orderBy('name')
            ->get()
            ->map(fn ($site) => [
                'id' => $site->id,
                'site_code' => $site->site_code,
                'name' => $site->name,
                'active' => (bool) $site->active,
                'cleaners' => $site->cleaners_count,
                'open_sessions' => $site->open_sessions_count,
                'today_checkins' => $site->today_checkins_count,
                'supervisor_name' => $site->supervisor_name,
                'supervisor_phone' => $site->supervisor_phone,
            ])->values()->all();
    }

    public function exceptions(array $filters = []): array
    {
        $hours = (int) ($filters['max_open_hours'] ?? config('security_cleaners.max_open_session_hours', 16));
        $cutoff = now()->subHours($hours);

        $longOpen = CleanerSiteLog::query()
            ->with(['cleaner:id,name,cleaner_code', 'site:id,name'])
            ->whereNull('checked_out_at')
            ->where('checked_in_at', '<=', $cutoff)
            ->oldest('checked_in_at')
            ->get();

        $unapproved = Cleaner::query()
            ->where('status', 'pending')
            ->oldest('created_at')
            ->get(['id', 'cleaner_code', 'name', 'vendor_name', 'site_name', 'created_at']);

        return [
            'max_open_session_hours' => $hours,
            'long_open_sessions' => $longOpen->map(fn ($log) => [
                'id' => $log->id,
                'cleaner' => optional($log->cleaner)->name,
                'cleaner_code' => optional($log->cleaner)->cleaner_code,
                'site' => optional($log->site)->name ?: $log->site_name,
                'checked_in_at' => optional($log->checked_in_at)->toIso8601String(),
                'hours_open' => $log->checked_in_at ? round($log->checked_in_at->diffInMinutes(now()) / 60, 2) : null,
            ])->values()->all(),
            'pending_approvals' => $unapproved->map(fn ($cleaner) => [
                'id' => $cleaner->id,
                'cleaner_code' => $cleaner->cleaner_code,
                'name' => $cleaner->name,
                'vendor_name' => $cleaner->vendor_name,
                'site_name' => $cleaner->site_name,
                'created_at' => optional($cleaner->created_at)->toIso8601String(),
            ])->values()->all(),
        ];
    }
}
