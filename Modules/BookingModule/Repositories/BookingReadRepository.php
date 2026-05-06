<?php

namespace Modules\BookingModule\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Modules\BookingModule\Models\CleaningBooking;
use Modules\BookingModule\Support\TenantContext;

class BookingReadRepository
{
    public function queryForCompany(?int $companyId = null): Builder
    {
        $companyId = $companyId ?: TenantContext::companyId();
        $query = CleaningBooking::query()->withoutGlobalScopes();

        if (!$companyId) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where('company_id', $companyId);
    }
}
