<?php

namespace Modules\TitanRewind\Filament\Resources\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait OwnerOnlyRewindAccess
{
    public static function canViewAny(): bool
    {
        return (bool) auth()->user()?->hasRole('owner');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        $companyId = auth()->user()?->organization_id;

        if ($companyId === null) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        return parent::getEloquentQuery()->where('company_id', $companyId);
    }
}
