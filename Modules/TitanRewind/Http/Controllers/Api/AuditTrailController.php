<?php

namespace Modules\TitanRewind\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\TitanRewind\Models\RewindEvent;

class AuditTrailController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $actor = $request->user();
        $isSuperAdmin = (bool) $actor?->hasRole('super_admin');
        $crossTenant = $request->boolean('cross_tenant') && $isSuperAdmin;

        $query = $crossTenant
            ? RewindEvent::query()->withoutGlobalScopes()
            : RewindEvent::query()->where('company_id', (int) ($actor?->company_id ?? 0));

        if ($crossTenant) {
            Log::channel('audit')->info('TitanRewind cross-tenant audit query executed.', [
                'actor_id' => $actor?->id,
                'route' => $request->path(),
            ]);
        }

        $events = $query->latest('id')->limit((int) $request->integer('limit', 50))->get();

        return response()->json([
            'data' => $events,
            'cross_tenant' => $crossTenant,
        ]);
    }
}
