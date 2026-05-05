<?php

namespace Modules\CleaningJobs\ControlPanel\Tables\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * BaseTableProvider provides a skeleton for generating tabular data used in the
 * lower half of the TitanWork control panel.
 *
 * Subclasses should override the query() and transform() methods to return
 * Eloquent queries and transform the results into arrays suitable for
 * presentation in a table component.
 */
abstract class BaseTableProvider
{
    /**
     * Return the base query for this table. Override this method in each
     * provider to fetch the desired data.
     */
    public function query(): Builder
    {
        // Default to an empty builder; subclasses must override
        return new Builder(new \Illuminate\Database\Eloquent\Model());
    }

    /**
     * Transform the raw results into a collection for rendering.
     */
    public function transform(): Collection
    {
        return collect($this->query()->get())->map(function ($model) {
            return $model->toArray();
        });
    }
}