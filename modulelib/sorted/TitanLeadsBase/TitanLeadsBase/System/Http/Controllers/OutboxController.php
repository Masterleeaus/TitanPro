<?php

namespace App\Extensions\TitanLeads\System\Http\Controllers;

use App\Extensions\TitanLeads\System\Models\OutboxDraft;
use App\Extensions\TitanLeads\System\Services\Outbox\OutboxService;
use App\Helpers\Classes\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class OutboxController extends Controller
{
    public function __construct(public OutboxService $outbox) {}

    public function index()
    {
        return view('titan-leads::outbox.index');
    }

    public function list(Request $request): JsonResponse
    {
        $drafts = OutboxDraft::query()->where('user_id', Auth::id())->latest()->paginate(30);
        return response()->json($drafts);
    }

    public function store(Request $request): JsonResponse
    {
        if (Helper::appIsDemo()) {
            return response()->json(['status' => 'error', 'message' => 'This feature is disabled in Demo version.'], 403);
        }

        $data = $request->validate([
            'channel' => 'required|string|in:sms,email,voice',
            'to' => 'required|string',
            'subject' => 'nullable|string',
            'body' => 'required|string',
            'conversation_id' => 'nullable|integer',
            'is_ai_generated' => 'nullable|boolean',
        ]);

        $draft = $this->outbox->createDraft([
            'user_id' => Auth::id(),
            'conversation_id' => $data['conversation_id'] ?? null,
            'channel' => $data['channel'],
            'to' => $data['to'],
            'subject' => $data['subject'] ?? null,
            'body' => $data['body'],
            'status' => 'draft',
            'is_ai_generated' => (bool)($data['is_ai_generated'] ?? false),
            'requires_approval' => (bool)($data['is_ai_generated'] ?? false),
        ]);

        return response()->json(['status' => 'success', 'draft' => $draft]);
    }

    public function requestApproval(Request $request): JsonResponse
    {
        if (Helper::appIsDemo()) {
            return response()->json(['status' => 'error', 'message' => 'This feature is disabled in Demo version.'], 403);
        }

        $request->validate(['draft_id' => 'required|integer']);
        $draft = OutboxDraft::query()->where('user_id', Auth::id())->findOrFail((int)$request->draft_id);

        $approval = $this->outbox->requestTitanZeroApproval($draft);

        return response()->json([
            'status' => 'success',
            'approval' => $approval,
        ]);
    }

    public function sendNow(Request $request): JsonResponse
    {
        if (Helper::appIsDemo()) {
            return response()->json(['status' => 'error', 'message' => 'This feature is disabled in Demo version.'], 403);
        }

        try {
            $request->validate([
                'draft_id' => 'required|integer',
            ]);

            $draft = OutboxDraft::query()->where('user_id', Auth::id())->findOrFail((int)$request->draft_id);
            $result = $this->outbox->sendNow($draft, true);

            return response()->json($result);
        } catch (Throwable $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
