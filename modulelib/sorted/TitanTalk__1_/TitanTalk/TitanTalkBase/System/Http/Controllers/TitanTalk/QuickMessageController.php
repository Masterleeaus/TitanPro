<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Http\Controllers\TitanTalk;

use App\Extensions\MarketingBot\System\Models\MarketingConversation;
use App\Extensions\MarketingBot\System\Services\TitanTalk\PresetMessageService;
use App\Helpers\Classes\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuickMessageController extends Controller
{
    public function __construct(protected PresetMessageService $presetMessageService) {}

    public function store(Request $request): JsonResponse
    {
        if (Helper::appIsDemo()) {
            return response()->json([
                'status' => false,
                'message' => 'This feature is disabled in Demo version.',
            ], 403);
        }

        $data = $request->validate([
            'conversation_id' => ['required', 'integer'],
            'preset' => ['required', 'string'],
            'name' => ['nullable', 'string'],
            'delay' => ['nullable', 'string'],
            'eta' => ['nullable', 'string'],
            'job_time' => ['nullable', 'string'],
            'review_link' => ['nullable', 'string'],
        ]);

        $conversation = MarketingConversation::query()
            ->where('user_id', Auth::id())
            ->findOrFail((int) $data['conversation_id']);

        $result = $this->presetMessageService->sendPreset($conversation, (string) $data['preset'], $data);

        return response()->json($result, ($result['status'] ?? false) ? 200 : 422);
    }
}
