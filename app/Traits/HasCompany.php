<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

            if (! isset(Auth::user()->company_id)) {
                return;
            }

            $authenticatedCompanyId = (int) Auth::user()->company_id;

            if ($model->company_id === null) {
                $model->company_id = $authenticatedCompanyId;

                return;
            }

            if ((int) $model->company_id !== $authenticatedCompanyId) {
                $model->company_id = $authenticatedCompanyId;
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
            } catch (\Throwable $exception) {
                Log::debug('HasCompany global scope skipped due to schema/auth resolution error.', [
                    'model' => get_class($builder->getModel()),
                    'error' => $exception->getMessage(),
                ]);
            }
        });
    }
}
