<?php

namespace Modules\CRMCore\Traits;

use Illuminate\Support\Facades\Schema;
use Modules\CRMCore\Scopes\ScopedByCompany;

trait UsesScopedByCompany
{
    /**
     * @var array<string, bool>
     */
    protected static array $hasCompanyColumnCache = [];

    protected static function bootUsesScopedByCompany(): void
    {
        static::addGlobalScope(new ScopedByCompany);

        static::creating(function ($model): void {
            $table = $model->getTable();
            $hasCompanyColumn = self::$hasCompanyColumnCache[$table]
                ??= Schema::hasColumn($table, 'company_id');

            if (! $hasCompanyColumn) {
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
