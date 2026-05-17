<?php

namespace Modules\InstantAds\Support\Scopes;

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
        $headerCompanyId = request()?->header('X-Company-Id');
        if (is_numeric($headerCompanyId)) {
            $authenticatedUser = auth()->user();
            if ($authenticatedUser && isset($authenticatedUser->company_id) && is_numeric($authenticatedUser->company_id)) {
                $authenticatedCompanyId = (int) $authenticatedUser->company_id;

                return $authenticatedCompanyId === (int) $headerCompanyId
                    ? $authenticatedCompanyId
                    : null;
            }

            return (int) $headerCompanyId;
        }

        $user = auth()->user();

        if ($user && isset($user->company_id) && is_numeric($user->company_id)) {
            return (int) $user->company_id;
        }

        return null;
    }

    private function tableHasCompanyColumn(Model $model): bool
    {
        return self::$tableHasCompanyColumn[$model->getTable()]
            ??= Schema::hasColumn($model->getTable(), 'company_id');
    }
}
