<?php

namespace App\Extensions\TitanOperator\System\Http\Controllers\ClientPortal;

use App\Extensions\TitanOperator\System\Services\ClientPortal\ClientPortalBuilderDataService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ClientPortalInboxController
{
    public function __construct(protected ClientPortalBuilderDataService $builderDataService) {}

    public function inbox(Request $request): View
    {
        return view('titan_operator::default.panel.user.client-portal.inbox', $this->builderDataService->dashboardPayload($request));
    }

    public function templates(Request $request): View
    {
        return view('titan_operator::default.panel.user.client-portal.templates', $this->builderDataService->dashboardPayload($request));
    }

    public function runtime(Request $request): View
    {
        return view('titan_operator::default.panel.user.client-portal.runtime-dashboard', $this->builderDataService->dashboardPayload($request));
    }
}
