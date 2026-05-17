<?php

declare(strict_types=1);

namespace Modules\Dispatch\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToTenant
{
    public function scopeForCompany(Builder $query, ?int $companyId): Builder
    {
        return $companyId ? $query->where($this->getTable().'.company_id', $companyId) : $query;
    }

    public function scopeForTenant(Builder $query, ?int $companyId): Builder
    {
        return $this->scopeForCompany($query, $companyId);
    }
}
