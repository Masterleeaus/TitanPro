<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Http\Controllers\TitanTalk;

use App\Extensions\MarketingBot\System\Models\MarketingConversation;
use App\Extensions\MarketingBot\System\Services\TitanTalk\OperatorCopilotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class OperatorCopilotController extends Controller
{
    public function context(MarketingConversation $conversation, OperatorCopilotService $operatorCopilotService): JsonResponse
    {
        return response()->json($operatorCopilotService->summarize($conversation));
    }

    public function summary(MarketingConversation $conversation, OperatorCopilotService $operatorCopilotService): JsonResponse
    {
        return response()->json($operatorCopilotService->summarize($conversation));
    }

    public function suggest(MarketingConversation $conversation, OperatorCopilotService $operatorCopilotService, Request $request): JsonResponse
    {
        return response()->json($operatorCopilotService->suggestReply($conversation));
    }
}
