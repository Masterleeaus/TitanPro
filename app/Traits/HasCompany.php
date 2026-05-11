<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

trait HasCompany
{
    protected static function bootHasCompany(): void
    {
        static::creating(function ($model): void {
            if (! Auth::check()) {
                return;
            }

            if (! Schema::hasColumn($model->getTable(), 'company_id')) {
                return;
            }

            if (empty($model->company_id) && isset(Auth::user()->company_id)) {
                $model->company_id = (int) Auth::user()->company_id;
            }
        });

        static::addGlobalScope('company_id', function (Builder $builder): void {
            try {
                if (! Auth::check() || ! isset(Auth::user()->company_id)) {
                    return;
                }

                $model = $builder->getModel();
                if (! Schema::hasColumn($model->getTable(), 'company_id')) {
                    return;
                }

                $builder->where($model->getTable().'.company_id', (int) Auth::user()->company_id);
            } catch (\Throwable) {
                // Fail-open during early boot / migration windows.
            }
        });
    }
}
