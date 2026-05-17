<?php

namespace Modules\CleaningJobs\ControlPanel\Tables\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Modules\CleaningJobs\Models\WORequest;

class RequestsTableProvider extends BaseTableProvider
{
    public function query(): Builder
    {
        return WORequest::query()->latest()->limit(10);
    }

    public function transform(): Collection
    {
        return $this->query()->get()->map(fn (WORequest $request): array => [
            'id' => $request->id,
            'request_detail' => $request->request_detail,
            'priority' => $request->priority,
            'status' => $request->status,
            'due_date' => optional($request->due_date)->toDateString(),
            'assign' => $request->assign,
        ]);
    }
}
