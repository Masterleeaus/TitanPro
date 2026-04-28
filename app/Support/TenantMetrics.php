<?php

namespace App\Support;

use App\Models\Invoice;
use App\Models\Job;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Builder;

/**
 * TenantMetrics — tenant-aware query helpers scoped to the current user's
 * organisation. All methods respect the organization_id of the authenticated
 * user and return zero / empty results when no organisation context exists.
 *
 * This complements CleaningAdminMetrics, providing lightweight helpers that
 * can be consumed directly from Filament widgets without pulling in the full
 * reporting surface.
 */
class TenantMetrics
{
    /**
     * Resolve the organization_id for the currently authenticated user.
     */
    public static function organizationId(): ?int
    {
        return auth()->user()?->organization_id;
    }

    /**
     * Scope an Eloquent query to the current tenant's organisation.
     *
     * @template TModel of \Illuminate\Database\Eloquent\Model
     * @param  Builder<TModel>  $query
     * @param  string  $column
     * @return Builder<TModel>
     */
    public static function scope(Builder $query, string $column = 'organization_id'): Builder
    {
        $id = self::organizationId();

        if (! $id) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where($column, $id);
    }

    // -------------------------------------------------------------------------
    // Revenue
    // -------------------------------------------------------------------------

    /**
     * Total confirmed revenue collected this month.
     */
    public static function revenueThisMonth(): float
    {
        $start = now()->startOfMonth();
        $end   = now()->endOfMonth();

        return (float) self::scope(Payment::query())
            ->whereBetween('paid_at', [$start, $end])
            ->sum('amount');
    }

    /**
     * Total confirmed revenue collected this financial year (Jul–Jun for AU).
     *
     * @param  int  $fyStartMonth  First month of the financial year (default 7 = July).
     */
    public static function revenueThisYear(int $fyStartMonth = 7): float
    {
        $now   = now();
        $year  = $now->month >= $fyStartMonth ? $now->year : $now->year - 1;
        $start = now()->setDate($year, $fyStartMonth, 1)->startOfDay();

        return (float) self::scope(Payment::query())
            ->where('paid_at', '>=', $start)
            ->sum('amount');
    }

    // -------------------------------------------------------------------------
    // Invoices
    // -------------------------------------------------------------------------

    /**
     * Count of invoices currently overdue.
     */
    public static function overdueInvoicesCount(): int
    {
        return (int) self::scope(Invoice::query())
            ->where(function (Builder $query): void {
                $query->where('status', Invoice::STATUS_OVERDUE)
                    ->orWhere(fn (Builder $nested) => $nested
                        ->whereNotIn('status', [Invoice::STATUS_PAID, Invoice::STATUS_VOID])
                        ->whereDate('due_at', '<', now()->toDateString()));
            })
            ->count();
    }

    /**
     * Total outstanding balance across all unpaid/overdue invoices.
     */
    public static function outstandingBalance(): float
    {
        return (float) self::scope(Invoice::query())
            ->whereIn('status', [
                Invoice::STATUS_SENT,
                Invoice::STATUS_PARTIAL,
                Invoice::STATUS_OVERDUE,
            ])
            ->sum('balance_due');
    }

    // -------------------------------------------------------------------------
    // Jobs
    // -------------------------------------------------------------------------

    /**
     * Count of jobs scheduled for today.
     */
    public static function jobsToday(): int
    {
        return (int) self::scope(Job::query())
            ->whereBetween('scheduled_at', [now()->startOfDay(), now()->endOfDay()])
            ->count();
    }

    /**
     * Count of jobs that are unassigned and not yet completed or cancelled.
     */
    public static function unassignedJobs(): int
    {
        return (int) self::scope(Job::query())
            ->whereNull('assigned_to')
            ->whereNotIn('status', [Job::STATUS_COMPLETED, Job::STATUS_CANCELLED])
            ->count();
    }

    /**
     * Count of jobs currently active (en-route or in-progress).
     */
    public static function activeJobs(): int
    {
        return (int) self::scope(Job::query())
            ->whereIn('status', [Job::STATUS_EN_ROUTE, Job::STATUS_IN_PROGRESS])
            ->count();
    }
}
