<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Http\Controllers\TitanTalk;

use App\Extensions\MarketingBot\System\Memory\ConversationContextBuilder;
use App\Extensions\MarketingBot\System\Models\MarketingConversation;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class ConversationContextController extends Controller
{
    public function __invoke(MarketingConversation $conversation, ConversationContextBuilder $conversationContextBuilder): JsonResponse
    {
        return response()->json($conversationContextBuilder->build($conversation));
    }
}
