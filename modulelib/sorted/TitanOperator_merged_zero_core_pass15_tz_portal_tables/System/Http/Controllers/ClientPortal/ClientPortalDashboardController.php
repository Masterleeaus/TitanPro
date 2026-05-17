<?php

namespace App\Extensions\TitanOperator\System\Http\Controllers\ClientPortal;

use App\Extensions\TitanOperator\System\Services\ClientPortal\ClientPortalBuilderDataService;
use App\Extensions\TitanOperator\System\Models\TitanOperator;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ClientPortalDashboardController
{
    public function __construct(protected ClientPortalBuilderDataService $builderDataService) {}

    public function index(Request $request): View
    {
        return view('titan_operator::default.panel.user.client-portal.index', $this->builderDataService->dashboardPayload($request));
    }

    public function history(Request $request): View
    {
        return view('titan_operator::default.panel.user.client-portal.history', $this->builderDataService->dashboardPayload($request));
    }

    public function builder(Request $request): View
    {
        return view('titan_operator::default.panel.user.client-portal.builder', $this->builderDataService->dashboardPayload($request));
    }

    public function preview(Request $request): View
    {
        return view('titan_operator::default.panel.user.client-portal.preview', $this->builderDataService->dashboardPayload($request));
    }

    public function embed(Request $request, TitanOperator $titan_operator): View
    {
        return view('titan_operator::default.panel.user.client-portal.embed', $this->builderDataService->embedPayload($request, $titan_operator));
    }

    public function install(Request $request, ?TitanOperator $titan_operator = null): View
    {
        return view('titan_operator::default.panel.user.client-portal.install', $this->builderDataService->installPayload($request, $titan_operator));
    }
}
