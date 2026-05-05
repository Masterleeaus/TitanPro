<?php

namespace Modules\BookingModule\Services;

use Modules\BookingModule\Queries\BookingOverviewQuery;
use Modules\BookingModule\Services\Contracts\BookingModuleServiceContract;

class BookingModuleService implements BookingModuleServiceContract
{
    public function __construct(protected BookingOverviewQuery $overviewQuery) {}

    public function health(?int $companyId = null): array
    {
        return [
            'module' => 'bookingmodule',
            'company_id' => $companyId,
            'overview' => $this->overview($companyId),
        ];
    }

    public function overview(?int $companyId = null): array
    {
        return $this->overviewQuery->counts($companyId);
    }
}
