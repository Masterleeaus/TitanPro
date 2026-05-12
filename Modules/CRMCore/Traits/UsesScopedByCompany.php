<?php

namespace Modules\CRMCore\Traits;

use Illuminate\Support\Facades\Schema;
use Modules\CRMCore\Scopes\ScopedByCompany;

trait UsesScopedByCompany
{
    protected static function bootUsesScopedByCompany(): void
    {
        static::addGlobalScope(new ScopedByCompany);

        static::creating(function ($model): void {
            if (! Schema::hasColumn($model->getTable(), 'company_id')) {
                return;
            }

            if ($model->company_id !== null) {
                return;
            }

            $companyId = ScopedByCompany::resolveCompanyId();
            if ($companyId !== null) {
                $model->company_id = $companyId;
            }
        });
    }
}
