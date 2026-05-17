<?php

namespace Modules\GroundZeroOps\Support\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Schema;

class ScopedByCompany implements Scope
{
    /**
     * @var array<string, bool>
     */
    private static array $tableHasCompanyColumn = [];

    public function apply(Builder $builder, Model $model): void
    {
        if (! $this->tableHasCompanyColumn($model)) {
            return;
        }

        $companyId = self::resolveCompanyId();

        if ($companyId === null) {
            return;
        }

        $builder->where($model->qualifyColumn('company_id'), $companyId);
    }

    public static function resolveCompanyId(): ?int
    {
        $user = auth()->user();

        if ($user && isset($user->company_id) && is_numeric($user->company_id)) {
            return (int) $user->company_id;
        }

        if (function_exists('company')) {
            $company = company();

            if ($company && isset($company->id) && is_numeric($company->id)) {
                return (int) $company->id;
            }
        }

        return null;
    }

    private function tableHasCompanyColumn(Model $model): bool
    {
        return self::$tableHasCompanyColumn[$model->getTable()]
            ??= Schema::hasColumn($model->getTable(), 'company_id');
    }
}
