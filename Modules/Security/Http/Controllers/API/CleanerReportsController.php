<?php

namespace Modules\Security\Http\Controllers\API;

use Illuminate\Routing\Controller;
use Modules\Security\Contracts\Services\CleanerReportingServiceInterface;

class CleanerReportsController extends Controller
{
    public function __construct(private readonly CleanerReportingServiceInterface $reports)
    {
    }

    public function daily()
    {
        return response()->json(['data' => $this->reports->daily(request()->all())]);
    }

    public function sites()
    {
        return response()->json(['data' => $this->reports->siteSummary(request()->all())]);
    }

    public function exceptions()
    {
        return response()->json(['data' => $this->reports->exceptions(request()->all())]);
    }
}
