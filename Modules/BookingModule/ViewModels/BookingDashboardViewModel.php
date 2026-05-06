<?php

namespace Modules\BookingModule\ViewModels;

use Modules\BookingModule\Queries\BookingOverviewQuery;

class BookingDashboardViewModel
{
    public function __construct(protected BookingOverviewQuery $overview) {}

    public function toArray(?int $companyId = null): array
    {
        return [
            'counts' => $this->overview->counts($companyId),
            'module' => 'bookingmodule',
        ];
    }
}
