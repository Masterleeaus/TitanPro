<?php

namespace App\Extensions\ProductPhotography\Services;

use App\Extensions\ProductPhotography\Models\QuoteVisual;
use Illuminate\Support\Collection;

class QuoteVisualRepository
{
    public function create(array $attributes): QuoteVisual
    {
        return QuoteVisual::create($attributes);
    }

    public function latest(int $limit = 12): Collection
    {
        return QuoteVisual::query()->latest()->limit($limit)->get();
    }
}
