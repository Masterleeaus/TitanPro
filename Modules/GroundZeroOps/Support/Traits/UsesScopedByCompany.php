<?php

namespace Modules\GroundZeroOps\Support\Traits;

use Illuminate\Support\Facades\Schema;
use Modules\GroundZeroOps\Support\Scopes\ScopedByCompany;

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

            if (! $hasCompanyColumn || $model->company_id !== null) {
                return;
            }

            $companyId = ScopedByCompany::resolveCompanyId();

            if ($companyId !== null) {
                $model->company_id = $companyId;
            }
        });
    }
}
