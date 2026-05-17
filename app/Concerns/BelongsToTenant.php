<?php

namespace App\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant(): void
    {
        static::addGlobalScope('tenant_company', function (Builder $builder): void {
            $tenantId = static::resolveTenantCompanyId();
            if ($tenantId === null) {
                return;
            }

            $model = $builder->getModel();
            if (! Schema::hasTable($model->getTable()) || ! Schema::hasColumn($model->getTable(), 'company_id')) {
                return;
            }

            $builder->where($model->qualifyColumn('company_id'), $tenantId);
        });

        static::creating(function (Model $model): void {
            static::enforceTenantCompanyId($model);
        });

        static::saving(function (Model $model): void {
            static::enforceTenantCompanyId($model);
        });
    }

    protected static function resolveTenantCompanyId(): ?int
    {
        $user = Auth::user();
        if (! $user) {
            return null;
        }

        $companyId = $user->company_id ?? $user->organization_id ?? null;

        return is_numeric($companyId) ? (int) $companyId : null;
    }

    protected static function enforceTenantCompanyId(Model $model): void
    {
        $tenantId = static::resolveTenantCompanyId();
        if ($tenantId === null) {
            return;
        }

        if (! Schema::hasTable($model->getTable()) || ! Schema::hasColumn($model->getTable(), 'company_id')) {
            return;
        }

        if ($model->company_id === null) {
            $model->company_id = $tenantId;

            return;
        }

        if ((int) $model->company_id !== $tenantId) {
            throw ValidationException::withMessages([
                'company_id' => 'Cross-tenant company assignment is not allowed.',
            ]);
        }
    }
}
