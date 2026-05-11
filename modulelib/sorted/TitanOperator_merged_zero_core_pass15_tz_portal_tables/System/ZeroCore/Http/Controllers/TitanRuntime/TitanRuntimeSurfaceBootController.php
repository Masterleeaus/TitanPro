<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Http\Controllers\TitanRuntime;

use App\Http\Controllers\Controller;
use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanRuntime\Engines\RuntimeOrchestrator;
use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanRuntime\Registry\SurfaceRegistry;
use Illuminate\Http\JsonResponse;

class TitanRuntimeSurfaceBootController extends Controller
{
    public function __construct(
        protected RuntimeOrchestrator $runtime,
        protected SurfaceRegistry $surfaces,
    ) {
    }

    public function boot(string $surface = "work"): JsonResponse
    {
        $meta = $this->surfaces->get($surface);
        $boot = $this->runtime->boot($meta['hub'] ?? 'work');

        return response()->json([
            'surface' => $meta,
            'boot' => $boot,
        ]);
    }

    public function show(string $surface): JsonResponse
    {
        return $this->boot($surface);
    }
}
