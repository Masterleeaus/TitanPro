<?php

namespace Modules\CleaningJobs\Tenancy\Resolvers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class CompanyTenantResolver
{
    /**
     * @var array<string, array<string, bool>>
     */
    private array $columnCache = [];

    public function currentCompanyId(?Authenticatable $user = null): ?int
    {
        $user ??= auth()->user();

        if (! $user) {
            return null;
        }

        foreach (['company_id', 'organization_id', 'parent_id'] as $column) {
            $value = data_get($user, $column);

            if ($value !== null) {
                return (int) $value;
            }
        }

        return null;
    }

    public function applyToBuilder(Builder $builder, ?int $companyId = null): void
    {
        $companyId ??= $this->currentCompanyId();

        if ($companyId === null) {
            return;
        }

        $model = $builder->getModel();
        $hasCompanyId = $this->hasColumn($model, 'company_id');
        $hasParentId = $this->hasColumn($model, 'parent_id');

        if (! $hasCompanyId && ! $hasParentId) {
            return;
        }

        $builder->where(function (Builder $query) use ($companyId, $hasCompanyId, $hasParentId, $model): void {
            $hasClause = false;

            if ($hasCompanyId) {
                $query->where($model->qualifyColumn('company_id'), $companyId);
                $hasClause = true;
            }

            if ($hasParentId) {
                $method = $hasClause ? 'orWhere' : 'where';

                $query->{$method}(function (Builder $fallback) use ($companyId, $hasCompanyId, $model): void {
                    if ($hasCompanyId) {
                        $fallback->whereNull($model->qualifyColumn('company_id'));
                    }

                    $fallback->where($model->qualifyColumn('parent_id'), $companyId);
                });
            }
        });
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @return array<string, mixed>
     */
    public function stampAttributes(array $attributes, ?Authenticatable $user = null): array
    {
        $companyId = $this->currentCompanyId($user);

        if ($companyId === null) {
            return $attributes;
        }

        if (! array_key_exists('company_id', $attributes) || $attributes['company_id'] === null) {
            $attributes['company_id'] = $companyId;
        }

        if (! array_key_exists('parent_id', $attributes) || $attributes['parent_id'] === null) {
            $attributes['parent_id'] = $companyId;
        }

        return $attributes;
    }

    public function stampModel(Model $model, ?Authenticatable $user = null): void
    {
        $companyId = $this->currentCompanyId($user);

        if ($companyId === null) {
            return;
        }

        if ($this->hasColumn($model, 'company_id') && blank($model->getAttribute('company_id'))) {
            $model->setAttribute('company_id', $companyId);
        }

        if ($this->hasColumn($model, 'parent_id') && blank($model->getAttribute('parent_id'))) {
            $model->setAttribute('parent_id', $companyId);
        }
    }

    public function belongsToCurrentTenant(Model $model, ?Authenticatable $user = null): bool
    {
        $companyId = $this->currentCompanyId($user);

        if ($companyId === null) {
            return false;
        }

        $modelCompanyId = data_get($model, 'company_id');
        if ($modelCompanyId !== null) {
            return (int) $modelCompanyId === $companyId;
        }

        $modelParentId = data_get($model, 'parent_id');

        return $modelParentId !== null && (int) $modelParentId === $companyId;
    }

    public function hasColumn(Model $model, string $column): bool
    {
        $table = $model->getTable();

        if (! isset($this->columnCache[$table][$column])) {
            $this->columnCache[$table][$column] = Schema::hasColumn($table, $column);
        }

        return $this->columnCache[$table][$column];
    }
}
