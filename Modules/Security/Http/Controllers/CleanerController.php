<?php

namespace Modules\Security\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Security\Contracts\Services\CleanerOperationsServiceInterface;
use Modules\Security\Entities\Cleaner;
use Modules\Security\Entities\CleanerSite;
use Modules\Security\Http\Requests\CleanerApprovalRequest;
use Modules\Security\Http\Requests\CleanerCheckInRequest;
use Modules\Security\Http\Requests\StoreCleanerRequest;

class CleanerController extends Controller
{
    public function __construct(private readonly CleanerOperationsServiceInterface $cleaners)
    {
    }

    public function index()
    {
        $cleaners = Cleaner::query()->with('site')->latest('id')->paginate(25);
        $sites = CleanerSite::query()->active()->orderBy('name')->get();
        $dashboard = $this->cleaners->dashboard();

        return view('security::cleaners.index', compact('cleaners', 'sites', 'dashboard'));
    }

    public function store(StoreCleanerRequest $request)
    {
        $this->cleaners->register($request->validated());

        return redirect()->route('security.cleaners.index')->with('success', 'Cleaner registered.');
    }

    public function approve(Cleaner $cleaner)
    {
        $this->cleaners->approve($cleaner, optional(request()->user())->id);

        return redirect()->route('security.cleaners.index')->with('success', 'Cleaner approved.');
    }

    public function decision(CleanerApprovalRequest $request, Cleaner $cleaner)
    {
        $payload = $request->validated();
        $this->cleaners->decide($cleaner, $payload['decision'], $payload, optional($request->user())->id);

        return redirect()->route('security.cleaners.index')->with('success', 'Cleaner decision saved.');
    }

    public function checkIn(CleanerCheckInRequest $request, Cleaner $cleaner)
    {
        $this->cleaners->checkIn($cleaner, $request->validated(), optional($request->user())->id);

        return redirect()->route('security.cleaners.index')->with('success', 'Cleaner checked in.');
    }

    public function checkOut(CleanerCheckInRequest $request, Cleaner $cleaner)
    {
        $this->cleaners->checkOut($cleaner, $request->validated(), optional($request->user())->id);

        return redirect()->route('security.cleaners.index')->with('success', 'Cleaner checked out.');
    }
}

