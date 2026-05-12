<?php

namespace Modules\CRMCore\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Schema;

class ScopedByCompany implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (! Schema::hasColumn($model->getTable(), 'company_id')) {
            return;
        }

        $companyId = $this->resolveCompanyId();
        if ($companyId === null) {
            return;
        }

        $builder->where($model->qualifyColumn('company_id'), $companyId);
    }

    public static function resolveCompanyId(): ?int
    {
        $headerCompanyId = request()?->header('X-Company-Id');
        if (is_numeric($headerCompanyId)) {
            return (int) $headerCompanyId;
        }

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
}
