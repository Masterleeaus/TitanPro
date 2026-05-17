<?php

namespace Modules\TitanEchoAssist\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\TitanEchoAssist\Models\Chatbot;
use Modules\TitanEchoAssist\Models\Conversation;

class ValidatePortalSessionToken
{
    public function handle(Request $request, Closure $next): mixed
    {
        /** @var Chatbot|string|null $chatbotParameter */
        $chatbotParameter = $request->route('chatbot');
        $sessionId = (string) $request->route('sessionId', '');

        if ($sessionId === '' || $chatbotParameter === null) {
            return $this->unauthorizedResponse();
        }

        $chatbot = $chatbotParameter instanceof Chatbot
            ? $chatbotParameter
            : Chatbot::query()->where('uuid', (string) $chatbotParameter)->first();

        if ($chatbot === null) {
            return $this->unauthorizedResponse();
        }

        $conversation = Conversation::query()
            ->where('chatbot_id', $chatbot->getKey())
            ->where('session_id', $sessionId)
            ->first();

        if ($conversation === null) {
            return $this->unauthorizedResponse();
        }

        if (
            $chatbot->company_id === null
            || $conversation->company_id === null
            || (int) $chatbot->company_id !== (int) $conversation->company_id
        ) {
            return $this->unauthorizedResponse();
        }

        $request->attributes->set('portalChatbot', $chatbot);
        $request->attributes->set('portalConversation', $conversation);

        return $next($request);
    }

    private function unauthorizedResponse(): JsonResponse
    {
        return response()->json([
            'ok' => false,
            'message' => 'Invalid portal session token.',
        ], 401);
    }
}
