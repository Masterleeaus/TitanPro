<?php

declare(strict_types=1);

namespace Modules\Budgeting\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Budgeting\Http\Resources\KpiSnapshotResource;
use Modules\Budgeting\Models\KpiSnapshot;

class KpiDashboardController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $snapshots = KpiSnapshot::query()
            ->where('company_id', $request->user()->company_id)
            ->orderByDesc('snapshot_date')
            ->paginate(config('budgeting.pagination.per_page', 25));

        return KpiSnapshotResource::collection($snapshots)->response();
    }
}
