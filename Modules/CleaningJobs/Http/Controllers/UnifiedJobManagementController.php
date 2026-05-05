<?php
namespace Modules\CleaningJobs\Http\Controllers;
use Illuminate\Routing\Controller;
use Modules\CleaningJobs\Services\CleaningJobUnifiedManagementService;
class UnifiedJobManagementController extends Controller
{
    public function capabilities(CleaningJobUnifiedManagementService $service)
    {
        return response()->json($service->capabilities());
    }
}
