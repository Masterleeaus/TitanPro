<?php

namespace App\Filament\TitanQuotes\Pages;

use App\Models\Estimate;
use Filament\Pages\Page;

class QuotePipelineDashboard extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-funnel';

    protected static ?string $title = 'Quote Pipeline';

    protected static ?string $navigationLabel = 'Quote Pipeline';

    protected static ?string $navigationGroup = 'Quotes';

    protected static ?string $slug = 'quote-pipeline';

    protected static ?int $navigationSort = 5;

    protected static string $view = 'filament.titanquotes.pages.quote-pipeline-dashboard';

    public function getViewData(): array
    {
        $organizationId = auth()->user()?->organization_id;

        if ($organizationId === null) {
            return [
                'draftCount' => 0,
                'sentCount' => 0,
                'acceptedCount' => 0,
                'convertedCount' => 0,
            ];
        }

        $baseQuery = Estimate::query()->where('organization_id', $organizationId);

        return [
            'draftCount' => (clone $baseQuery)->where('status', Estimate::STATUS_DRAFT)->count(),
            'sentCount' => (clone $baseQuery)->where('status', Estimate::STATUS_SENT)->count(),
            'acceptedCount' => (clone $baseQuery)->where('status', Estimate::STATUS_ACCEPTED)->count(),
            'convertedCount' => (clone $baseQuery)->whereHas('convertedJob')->count(),
        ];
    }
}
