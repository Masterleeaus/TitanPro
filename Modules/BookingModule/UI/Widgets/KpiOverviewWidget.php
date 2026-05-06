<?php

namespace Modules\BookingModule\UI\Widgets;

use Modules\BookingModule\Queries\BookingOverviewQuery;

class KpiOverviewWidget
{
    public function metrics(?int $companyId = null): array
    {
        return app(BookingOverviewQuery::class)->counts($companyId);
    }
}
