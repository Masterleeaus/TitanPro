<?php

namespace App\Filament\GroundZero\Pages;

use App\Models\Estimate;
use App\Models\Invoice;
use App\Models\Job;
use App\Models\JobType;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class ReportsPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar-square';

    protected static string|\UnitEnum|null $navigationGroup = 'Reporting';

    protected static ?string $navigationLabel = 'Reports';

    protected static ?string $title = 'Operational Reports';

    protected static ?int $navigationSort = 80;

    protected string $view = 'filament.groundzero.pages.reports';

    public function getReportData(): array
    {
        $organizationId = auth()->user()?->organization_id;

        if ($organizationId === null) {
            return $this->emptyReport();
        }

        $jobs = Job::query()->where('organization_id', $organizationId);

        $jobsByStatus = (clone $jobs)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->orderByDesc('total')
            ->pluck('total', 'status')
            ->mapWithKeys(fn ($count, $status) => [Job::statuses()[$status] ?? $status => $count]);

        $jobsByService = JobType::query()
            ->where('organization_id', $organizationId)
            ->withCount(['jobs as jobs_count' => fn ($query) => $query->where('organization_id', $organizationId)])
            ->orderByDesc('jobs_count')
            ->limit(10)
            ->get(['id', 'name']);

        $quotes = Estimate::query()->where('organization_id', $organizationId);
        $approvedQuotes = (clone $quotes)->whereIn('status', ['approved', 'accepted', 'won'])->count();
        $totalQuotes = (clone $quotes)->count();

        $invoiceTotals = Invoice::query()
            ->where('organization_id', $organizationId)
            ->selectRaw("coalesce(sum(total), 0) as total_revenue")
            ->selectRaw("coalesce(sum(case when status in ('sent','overdue','partial') then total else 0 end), 0) as outstanding")
            ->first();

        return [
            'jobsByStatus'    => $jobsByStatus,
            'jobsByService'   => $jobsByService,
            'quoteConversion' => $totalQuotes > 0 ? round(($approvedQuotes / $totalQuotes) * 100, 1) : 0,
            'totalQuotes'     => $totalQuotes,
            'approvedQuotes'  => $approvedQuotes,
            'totalRevenue'    => (float) ($invoiceTotals?->total_revenue ?? 0),
            'outstanding'     => (float) ($invoiceTotals?->outstanding ?? 0),
        ];
    }

    private function emptyReport(): array
    {
        return [
            'jobsByStatus'    => collect(),
            'jobsByService'   => collect(),
            'quoteConversion' => 0,
            'totalQuotes'     => 0,
            'approvedQuotes'  => 0,
            'totalRevenue'    => 0.0,
            'outstanding'     => 0.0,
        ];
    }
}
