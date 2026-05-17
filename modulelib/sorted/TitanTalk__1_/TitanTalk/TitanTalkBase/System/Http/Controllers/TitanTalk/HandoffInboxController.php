<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Http\Controllers\TitanTalk;

use App\Extensions\MarketingBot\System\Models\TitanTalk\Handoff;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class HandoffInboxController extends Controller
{
    public function index(Request $request)
    {
        $q = Handoff::query()->with('conversation');

        if ($request->filled('state')) {
            $q->where('state', (string) $request->get('state'));
        } else {
            $q->whereIn('state', ['open', 'assigned']);
        }

        if ($request->filled('priority')) {
            $q->where('priority', (string) $request->get('priority'));
        }

        if ($request->filled('channel')) {
            $q->where('channel', (string) $request->get('channel'));
        }

        $handoffs = $q->orderByRaw("CASE priority WHEN 'high' THEN 0 ELSE 1 END")
            ->orderByDesc('requested_at')
            ->paginate(30)
            ->withQueryString();

        return view('marketing-bot::handoffs.index', [
            'handoffs' => $handoffs,
            'filters' => $request->all(),
        ]);
    }

    public function assign(Request $request, int $id)
    {
        $handoff = Handoff::query()->findOrFail($id);
        $handoff->assigned_to_user_id = $request->filled('assigned_to_user_id') ? (int) $request->get('assigned_to_user_id') : null;
        $handoff->assigned_at = $handoff->assigned_to_user_id ? now() : null;
        $handoff->state = $handoff->assigned_to_user_id ? 'assigned' : 'open';
        $handoff->save();

        return back()->with('success', 'Handoff assignment updated.');
    }

    public function resolve(int $id)
    {
        $handoff = Handoff::query()->findOrFail($id);
        $handoff->state = 'resolved';
        $handoff->resolved_at = now();
        $handoff->save();

        return back()->with('success', 'Handoff resolved.');
    }
}
