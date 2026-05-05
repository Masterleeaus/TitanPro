<?php

namespace Modules\BookingModule\Filament\Widgets;

use Modules\BookingModule\Queries\BookingOverviewQuery;

class BookingStatsWidget
{
    public function data(?int $companyId = null): array
    {
        return app(BookingOverviewQuery::class)->counts($companyId);
    }
}
