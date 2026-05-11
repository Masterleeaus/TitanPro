<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Http\Controllers\TitanTalk;

use App\Extensions\MarketingBot\System\Models\MarketingConversation;
use App\Extensions\MarketingBot\System\Operator\OperatorActionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class OperatorActionController extends Controller
{
    public function store(MarketingConversation $conversation, Request $request, OperatorActionService $operatorActionService): JsonResponse
    {
        $data = $request->validate([
            'action' => ['required', 'string'],
            'payload' => ['nullable', 'array'],
        ]);

        return response()->json(
            $operatorActionService->execute($conversation, (string) $data['action'], (array) ($data['payload'] ?? []))
        );
    }
}
