<?php

namespace Modules\CleaningJobs\ControlPanel\Tables\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

abstract class BaseTableProvider
{
    abstract public function query(): Builder;

    public function transform(): Collection
    {
        return $this->query()->get()->map(function ($model) {
            return $model->toArray();
        });
    }
}
