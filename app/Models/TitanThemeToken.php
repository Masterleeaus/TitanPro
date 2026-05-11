<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TitanThemeToken extends Model
{
    protected $fillable = [
        'panel',
        'scope',
        'key',
        'value',
    ];

    public function scopeForPanel(Builder $query, ?string $panel): Builder
    {
        return $query->where('panel', $panel ?: 'global');
    }
}
