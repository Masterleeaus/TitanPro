<?php

namespace App\Filament\TitanSolo\Widgets;

use App\Models\Invoice;
use App\Models\Job;
use Filament\Widgets\Widget;

class SoloOverviewWidget extends Widget
{
    protected string $view = 'filament.titansolo.widgets.solo-overview-widget';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 1;

    protected function getViewData(): array
    {
        $organizationId = auth()->user()?->organization_id;

        if (! $organizationId) {
            return [
                'todayJobs' => collect(),
                'outstandingInvoices' => collect(),
            ];
        }

        return [
            'todayJobs' => Job::query()
                ->where('organization_id', $organizationId)
                ->whereDate('scheduled_at', today())
                ->with('customer:id,first_name,last_name')
                ->orderBy('scheduled_at')
                ->limit(8)
                ->get(),
            'outstandingInvoices' => Invoice::query()
                ->where('organization_id', $organizationId)
                ->where('balance_due', '>', 0)
                ->whereNotIn('status', [Invoice::STATUS_PAID, Invoice::STATUS_VOID])
                ->with('customer:id,first_name,last_name')
                ->orderBy('due_at')
                ->limit(8)
                ->get(),
        ];
    }
}
