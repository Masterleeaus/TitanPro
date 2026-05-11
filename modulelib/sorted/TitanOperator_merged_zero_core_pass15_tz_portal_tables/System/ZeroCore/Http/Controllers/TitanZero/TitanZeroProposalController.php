<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Http\Controllers\TitanZero;

use App\Http\Controllers\Controller;
use App\Extensions\TitanOperator\System\ZeroCore\Http\Requests\TitanZero\ZeroProposalRequest;
use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Systems\TitanZeroSystem;
use Illuminate\Http\RedirectResponse;

class TitanZeroProposalController extends Controller
{
    public function __construct(protected TitanZeroSystem $system) {}

    public function index()
    {
        return view('titan_operator::default.panel.user.titanzero.proposals', [
            'proposals' => $this->system->recentProposals(auth()->user()?->team_id),
        ]);
    }

    public function update(ZeroProposalRequest $request, int $proposal): RedirectResponse
    {
        $updated = $this->system->updateProposalStatus(
            $proposal,
            $request->string('status')->toString(),
            $request->input('notes')
        );

        return back()->with(
            $updated ? 'success' : 'warning',
            $updated ? 'Titan Zero proposal updated.' : 'Proposal not found for this tenant or tables are missing.'
        );
    }
}
