<?php

namespace Modules\TitanEchoAssist\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\TitanEchoAssist\Services\WorkcoreWorkerDataService;

class TitanGoWorkerController extends Controller
{
    public function __construct(private readonly WorkcoreWorkerDataService $workerDataService) {}

    public function jobsToday(Request $request): JsonResponse
    {
        $user = $request->user();
        $companyId = (int) ($user?->company_id ?? $user?->organization_id ?? 0);

        return response()->json([
            'data' => $this->workerDataService->getTechnicianTodaysJobs((int) $user->id, $companyId),
        ]);
    }

    public function currentJob(Request $request): JsonResponse
    {
        $user = $request->user();
        $companyId = (int) ($user?->company_id ?? $user?->organization_id ?? 0);

        return response()->json([
            'data' => $this->workerDataService->getCurrentJob((int) $user->id, $companyId),
        ]);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $user = $request->user();
        $companyId = (int) ($user?->company_id ?? $user?->organization_id ?? 0);

        return response()->json([
            'data' => $this->workerDataService->getJobDetails($id, $companyId),
            'checklist' => $this->workerDataService->getJobChecklist($id, $companyId),
        ]);
    }

    public function diary(Request $request): JsonResponse
    {
        $user = $request->user();
        $companyId = (int) ($user?->company_id ?? $user?->organization_id ?? 0);

        $data = $request->validate([
            'job_id' => ['required', 'integer'],
            'content' => ['required', 'string', 'max:5000'],
        ]);

        return response()->json([
            'data' => $this->workerDataService->createSiteDiaryEntry((int) $data['job_id'], $companyId, (string) $data['content']),
        ], 201);
    }
}
