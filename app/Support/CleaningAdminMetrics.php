<?php

namespace App\Support;

use App\Models\DriverLocation;
use App\Models\Estimate;
use App\Models\Invoice;
use App\Models\Job;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CleaningAdminMetrics
{
    public static function organizationId(): ?int
    {
        return auth()->user()?->organization_id;
    }

    public static function forOrganization(Builder $query, ?string $column = 'organization_id'): Builder
    {
        $organizationId = self::organizationId();

        if (! $organizationId) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where($column, $organizationId);
    }

    public static function invoices(): Builder
    {
        return self::forOrganization(Invoice::query());
    }

    public static function estimates(): Builder
    {
        return self::forOrganization(Estimate::query());
    }

    public static function jobs(): Builder
    {
        return self::forOrganization(Job::query());
    }

    public static function payments(): Builder
    {
        return self::forOrganization(Payment::query());
    }

    public static function cleaners(): Builder
    {
        return self::forOrganization(User::query());
    }

    public static function cleanerLocations(): Builder
    {
        $organizationId = self::organizationId();

        if (! $organizationId) {
            return DriverLocation::query()->whereRaw('1 = 0');
        }

        return DriverLocation::query()
            ->whereHas('user', fn (Builder $query) => $query->where('organization_id', $organizationId));
    }

    public static function currency(float|int|string|null $value): string
    {
        return '$' . number_format((float) ($value ?? 0), 2);
    }

    public static function dashboardTotals(): array
    {
        $todayStart = now()->startOfDay();
        $todayEnd = now()->endOfDay();
        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();

        $invoices = self::invoices();
        $jobs = self::jobs();
        $estimates = self::estimates();
        $payments = self::payments();

        return [
            'total_invoices' => (clone $invoices)->count(),
            'outstanding_balance' => (float) (clone $invoices)
                ->whereIn('status', [Invoice::STATUS_SENT, Invoice::STATUS_PARTIAL, Invoice::STATUS_OVERDUE])
                ->sum('balance_due'),
            'overdue_invoices' => (clone $invoices)
                ->where(function (Builder $query): void {
                    $query->where('status', Invoice::STATUS_OVERDUE)
                        ->orWhere(fn (Builder $nested) => $nested
                            ->whereNotIn('status', [Invoice::STATUS_PAID, Invoice::STATUS_VOID])
                            ->whereDate('due_at', '<', now()->toDateString()));
                })
                ->count(),
            'payments_this_month' => (float) (clone $payments)
                ->whereBetween('paid_at', [$monthStart, $monthEnd])
                ->sum('amount'),
            'jobs_today' => (clone $jobs)
                ->whereBetween('scheduled_at', [$todayStart, $todayEnd])
                ->count(),
            'unassigned_jobs' => (clone $jobs)
                ->whereNull('assigned_to')
                ->whereNotIn('status', [Job::STATUS_COMPLETED, Job::STATUS_CANCELLED])
                ->count(),
            'active_jobs' => (clone $jobs)
                ->whereIn('status', [Job::STATUS_EN_ROUTE, Job::STATUS_IN_PROGRESS])
                ->count(),
            'quotes_open' => (clone $estimates)
                ->whereIn('status', [Estimate::STATUS_DRAFT, Estimate::STATUS_SENT])
                ->count(),
            'quotes_accepted' => (clone $estimates)
                ->where('status', Estimate::STATUS_ACCEPTED)
                ->count(),
        ];
    }

    public static function latestCleanerLocations(int $limit = 20): Collection
    {
        $organizationId = self::organizationId();

        if (! $organizationId) {
            return collect();
        }

        $latestIds = DriverLocation::query()
            ->selectRaw('MAX(driver_locations.id) as id')
            ->join('users', 'users.id', '=', 'driver_locations.user_id')
            ->where('users.organization_id', $organizationId)
            ->groupBy('driver_locations.user_id')
            ->pluck('id');

        if ($latestIds->isEmpty()) {
            return collect();
        }

        return DriverLocation::query()
            ->with('user')
            ->whereIn('id', $latestIds)
            ->orderByDesc('recorded_at')
            ->limit($limit)
            ->get();
    }

    public static function invoiceStatusBreakdown(): Collection
    {
        return self::invoices()
            ->select('status', DB::raw('COUNT(*) as total'), DB::raw('SUM(balance_due) as balance_due'), DB::raw('SUM(total) as invoice_total'))
            ->groupBy('status')
            ->orderByDesc('total')
            ->get();
    }

    public static function monthlyRevenue(int $months = 12): Collection
    {
        $start = now()->subMonths($months - 1)->startOfMonth();

        return self::payments()
            ->whereNotNull('paid_at')
            ->where('paid_at', '>=', $start)
            ->orderBy('paid_at')
            ->get()
            ->groupBy(fn (Payment $payment) => $payment->paid_at?->format('Y-m') ?? 'unknown')
            ->map(fn (Collection $payments, string $period) => (object) [
                'period' => $period,
                'revenue' => (float) $payments->sum('amount'),
                'payments' => $payments->count(),
            ])
            ->values();
    }

    public static function cleanerWorkload(): Collection
    {
        return self::jobs()
            ->select('assigned_to', DB::raw('COUNT(*) as jobs'), DB::raw("SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed"))
            ->with('assignedTechnician:id,name')
            ->whereNotNull('assigned_to')
            ->groupBy('assigned_to')
            ->orderByDesc('jobs')
            ->limit(10)
            ->get();
    }

    public static function upcomingJobs(int $limit = 8): Collection
    {
        return self::jobs()
            ->with(['customer:id,first_name,last_name,business_name', 'property:id,address_line1,suburb,state', 'assignedTechnician:id,name', 'jobType:id,name'])
            ->whereNotIn('status', [Job::STATUS_COMPLETED, Job::STATUS_CANCELLED])
            ->whereNotNull('scheduled_at')
            ->orderBy('scheduled_at')
            ->limit($limit)
            ->get();
    }

    public static function servicePopularity(): Collection
    {
        return self::jobs()
            ->select('job_type_id', DB::raw('COUNT(*) as jobs'))
            ->with('jobType:id,name')
            ->whereNotNull('job_type_id')
            ->groupBy('job_type_id')
            ->orderByDesc('jobs')
            ->limit(10)
            ->get();
    }

    public static function staleCleanerThreshold(): Carbon
    {
        return now()->subMinutes(30);
    }
}
