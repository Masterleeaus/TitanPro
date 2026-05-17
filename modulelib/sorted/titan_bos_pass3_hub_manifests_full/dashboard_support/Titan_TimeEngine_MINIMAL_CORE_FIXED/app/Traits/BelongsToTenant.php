<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToTenant
{
    public static function bootBelongsToTenant(): void
    {
        static::creating(function ($model): void {
            $teamId = $model->resolveCurrentTeamId();
            if (! $teamId) {
                return;
            }

            if (empty($model->team_id)) {
                $model->team_id = $teamId;
            }

            if ($model->hasTenantColumn('company_id') && empty($model->company_id)) {
                $model->company_id = $teamId;
            }

            if ($model->hasTenantColumn('created_by_team_id') && empty($model->created_by_team_id)) {
                $model->created_by_team_id = $teamId;
            }

            if ($model->hasTenantColumn('user_id') && empty($model->user_id) && auth()->check()) {
                $model->user_id = auth()->id();
            }
        });

        static::updating(function ($model): void {
            if ($model->hasTenantColumn('company_id') && ! empty($model->team_id)) {
                $model->company_id = $model->team_id;
            }
        });

        static::addGlobalScope('tenant', function (Builder $builder): void {
            $model = $builder->getModel();
            $teamId = method_exists($model, 'resolveCurrentTeamId') ? $model->resolveCurrentTeamId() : null;

            if (! $teamId || ! method_exists($model, 'hasTenantColumn') || ! $model->hasTenantColumn('team_id')) {
                return;
            }

            $builder->where($model->getTable() . '.team_id', $teamId);
        });
    }

    public function resolveCurrentTeamId(): ?int
    {
        if (property_exists($this, 'tenantColumnValue') && $this->tenantColumnValue) {
            return (int) $this->tenantColumnValue;
        }

        $user = auth()->user();
        if (! $user) {
            return null;
        }

        foreach (['active_team_id', 'current_team_id', 'team_id', 'company_id'] as $field) {
            $value = data_get($user, $field);
            if ($value) {
                return (int) $value;
            }
        }

        return null;
    }

    public function hasTenantColumn(string $column): bool
    {
        $columns = [];

        if (property_exists($this, 'tenantColumns')) {
            $columns = array_merge($columns, $this->tenantColumns);
        }

        if (method_exists($this, 'getFillable')) {
            $columns = array_merge($columns, $this->getFillable());
        }

        if (method_exists($this, 'getGuarded')) {
            $columns = array_merge($columns, $this->getGuarded());
        }

        return in_array($column, array_unique($columns), true);
    }
}
