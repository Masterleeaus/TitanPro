<?php

namespace App\Extensions\TitanRewind\System\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Extensions\TitanRewind\System\Models\RewindCase;
use App\Extensions\TitanRewind\System\Models\RewindFix;
use App\Extensions\TitanRewind\System\Services\RewindCaseService;
use App\Extensions\TitanRewind\System\Services\RewindAuditService;
use App\Extensions\TitanRewind\System\Services\RewindFixService;

class TitanRewindCaseController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $companyId = $user->company_id ?? $user->id;

        $cases = RewindCase::query()
            ->where('company_id', $companyId)
            ->where('user_id', $user->id)
            ->orderByDesc('id')
            ->paginate(25);

        return view('titan-rewind::cases.index', compact('cases'));
    }

    public function show(Request $request, RewindCase $case)
    {
        $user = Auth::user();
        $this->assertTenant($case, $user);

        $events = $case->events()->orderByDesc('id')->limit(200)->get();
        $fixes = $case->fixes()->orderByDesc('id')->get();

        return view('titan-rewind::cases.show', compact('case','events','fixes'));
    }

    public function proposeFix(Request $request, RewindCase $case, RewindFixService $fixService, RewindAuditService $audit)
    {
        $user = Auth::user();
        $this->assertTenant($case, $user);

        $data = $request->validate([
            'fix_type' => 'required|string|max:80',
            'proposal_json' => 'nullable|string',
            'requires_confirmation' => 'nullable|boolean',
        ]);

        $proposalJson = [];
        if (!empty($data['proposal_json'])) {
            $proposalJson = json_decode($data['proposal_json'], true) ?: [];
        }
        $proposalJson['fix_type'] = $data['fix_type'];

        $fix = $fixService->proposeFix(
            $case,
            $proposalJson,
            ['type' => 'user', 'id' => $user->id],
            (bool)($data['requires_confirmation'] ?? true)
        );

        $audit->appendEvent([
            'company_id' => $case->company_id,
            'user_id' => $case->user_id,
            'case_id' => $case->id,
            'event_type' => 'fix_proposed',
            'entity_type' => 'titan_rewind_fixes',
            'entity_id' => $fix->id,
            'actor_type' => 'user',
            'actor_id' => $user->id,
            'payload_json' => ['fix_type' => $fix->fix_type],
            'idempotency_key' => 'fix_proposed:' . $fix->id,
        ]);

        return redirect()->route('titanrewind.cases.show', ['case' => $case->id])
            ->with('success', 'Fix proposed.');
    }

    public function applyFix(Request $request, RewindCase $case, RewindFixService $fixService, RewindAuditService $audit)
    {
        $user = Auth::user();
        $this->assertTenant($case, $user);

        $data = $request->validate([
            'fix_id' => 'required|integer',
            'confirm' => 'nullable|boolean',
        ]);

        $fix = RewindFix::query()
            ->where('id', $data['fix_id'])
            ->where('case_id', $case->id)
            ->where('company_id', $case->company_id)
            ->where('user_id', $case->user_id)
            ->firstOrFail();

        if (($data['confirm'] ?? false) && $fix->status === 'proposed') {
            $fixService->confirmFix($fix, ['type' => 'user', 'id' => $user->id]);

            $audit->appendEvent([
                'company_id' => $case->company_id,
                'user_id' => $case->user_id,
                'case_id' => $case->id,
                'event_type' => 'fix_confirmed',
                'entity_type' => 'titan_rewind_fixes',
                'entity_id' => $fix->id,
                'actor_type' => 'user',
                'actor_id' => $user->id,
                'payload_json' => ['fix_type' => $fix->fix_type],
                'idempotency_key' => 'fix_confirmed:' . $fix->id,
            ]);
        }

        $fixService->applyFix($fix, ['type' => 'user', 'id' => $user->id]);

        $audit->appendEvent([
            'company_id' => $case->company_id,
            'user_id' => $case->user_id,
            'case_id' => $case->id,
            'event_type' => 'fix_applied',
            'entity_type' => 'titan_rewind_fixes',
            'entity_id' => $fix->id,
            'actor_type' => 'user',
            'actor_id' => $user->id,
            'payload_json' => ['status' => $fix->status],
            'idempotency_key' => 'fix_applied:' . $fix->id,
        ]);

        return redirect()->route('titanrewind.cases.show', ['case' => $case->id])
            ->with('success', 'Fix processed.');
    }

    public function resolve(Request $request, RewindCase $case, RewindCaseService $caseService, RewindAuditService $audit)
    {
        $user = Auth::user();
        $this->assertTenant($case, $user);

        $caseService->resolveCase($case, ['type' => 'user', 'id' => $user->id]);

        $audit->appendEvent([
            'company_id' => $case->company_id,
            'user_id' => $case->user_id,
            'case_id' => $case->id,
            'event_type' => 'case_resolved',
            'actor_type' => 'user',
            'actor_id' => $user->id,
            'payload_json' => [],
            'idempotency_key' => 'case_resolved:' . $case->id,
        ]);

        return redirect()->route('titanrewind.cases.show', ['case' => $case->id])
            ->with('success', 'Case resolved.');
    }

    private function assertTenant(RewindCase $case, $user): void
    {
        $companyId = $user->company_id ?? $user->id;
        if ((int)$case->company_id !== (int)$companyId || (int)$case->user_id !== (int)$user->id) {
            abort(403);
        }
    }
}
