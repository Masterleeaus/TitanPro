<?php

namespace App\Extensions\TitanOperator\System\Http\Controllers\ClientPortal;

use App\Extensions\TitanOperator\System\Services\ClientPortal\ClientPortalBuilderDataService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientPortalApiController
{
    public function __construct(protected ClientPortalBuilderDataService $builderDataService) {}

    public function status(Request $request): JsonResponse
    {
        return response()->json($this->builderDataService->portalStatusPayload($request));
    }

    public function templates(Request $request): JsonResponse
    {
        return response()->json($this->builderDataService->templatesPayload($request));
    }
}
