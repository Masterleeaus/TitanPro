<?php

namespace Modules\Security\Http\Controllers\API;

use Illuminate\Routing\Controller;
use Modules\Security\Contracts\Services\CleanerOperationsServiceInterface;
use Modules\Security\Entities\Cleaner;
use Modules\Security\Http\Requests\CleanerApprovalRequest;
use Modules\Security\Http\Requests\CleanerCheckInRequest;
use Modules\Security\Http\Requests\StoreCleanerRequest;

class CleanerOperationsController extends Controller
{
    public function __construct(private readonly CleanerOperationsServiceInterface $cleaners)
    {
    }


    public function sites()
    {
        return response()->json([
            'data' => $this->cleaners->activeSites(),
        ]);
    }

    public function storeSite()
    {
        $data = request()->validate([
            'name' => ['required', 'string', 'max:120'],
            'site_code' => ['nullable', 'string', 'max:80'],
            'address' => ['nullable', 'string', 'max:255'],
            'supervisor_name' => ['nullable', 'string', 'max:120'],
            'supervisor_phone' => ['nullable', 'string', 'max:40'],
            'active' => ['nullable', 'boolean'],
            'meta' => ['nullable', 'array'],
        ]);

        $data['company_id'] = $data['company_id'] ?? optional(request()->user())->company_id;

        return response()->json([
            'data' => $this->cleaners->registerSite($data),
        ], 201);
    }

    public function index()
    {
        return response()->json([
            'data' => Cleaner::query()->with('site')->latest('id')->paginate(request('per_page', 20)),
        ]);
    }

    public function store(StoreCleanerRequest $request)
    {
        return response()->json([
            'data' => $this->cleaners->register($request->validated()),
        ], 201);
    }

    public function show(Cleaner $cleaner)
    {
        return response()->json([
            'data' => $cleaner->load(['site', 'siteLogs', 'accessCards', 'workPermits', 'goodsMovements']),
        ]);
    }

    public function approve(Cleaner $cleaner)
    {
        return response()->json([
            'data' => $this->cleaners->approve($cleaner, optional(request()->user())->id),
        ]);
    }

    public function decide(CleanerApprovalRequest $request, Cleaner $cleaner)
    {
        $payload = $request->validated();

        return response()->json([
            'data' => $this->cleaners->decide($cleaner, $payload['decision'], $payload, optional($request->user())->id),
        ]);
    }

    public function checkIn(CleanerCheckInRequest $request, Cleaner $cleaner)
    {
        return response()->json([
            'data' => $this->cleaners->checkIn($cleaner, $request->validated(), optional($request->user())->id),
        ]);
    }

    public function checkOut(CleanerCheckInRequest $request, Cleaner $cleaner)
    {
        return response()->json([
            'data' => $this->cleaners->checkOut($cleaner, $request->validated(), optional($request->user())->id),
        ]);
    }

    public function forceCheckOut(CleanerCheckInRequest $request, Cleaner $cleaner)
    {
        return response()->json([
            'data' => $this->cleaners->forceCheckOut($cleaner, $request->validated(), optional($request->user())->id),
        ]);
    }

    public function dashboard()
    {
        return response()->json($this->cleaners->dashboard());
    }
}
