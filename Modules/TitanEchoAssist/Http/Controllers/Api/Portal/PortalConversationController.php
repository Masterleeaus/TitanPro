<?php

namespace Modules\TitanEchoAssist\Http\Controllers\Api\Portal;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Modules\TitanEchoAssist\Models\Chatbot;
use Modules\TitanEchoAssist\Models\ChatbotHistory;
use Modules\TitanEchoAssist\Models\Conversation;

class PortalConversationController extends PortalBaseController
{
    public function store(Request $request, Chatbot $chatbot, string $sessionId): JsonResponse
    {
        $chatbot = $this->portalChatbot($request, $chatbot);
        $validated = $request->validate([
            'message' => 'required|string',
            'conversation_id' => 'nullable|integer',
        ]);

        $conversation = Conversation::query()
            ->where('chatbot_id', $chatbot->getKey())
            ->where('session_id', $sessionId)
            ->when(
                isset($validated['conversation_id']),
                fn ($query) => $query->where('id', $validated['conversation_id'])
            )
            ->first();

        if ($conversation === null) {
            $conversation = Conversation::query()->create([
                'chatbot_id' => $chatbot->getKey(),
                'session_id' => $sessionId,
                'company_id' => $chatbot->company_id,
                'conversation_name' => 'Portal Session',
                'chatbot_channel' => 'portal',
                'last_activity_at' => now(),
            ]);
        }

        ChatbotHistory::query()->create([
            'chatbot_id' => $chatbot->getKey(),
            'conversation_id' => $conversation->getKey(),
            'role' => 'user',
            'model' => $chatbot->ai_model,
            'message' => $validated['message'],
            'read_at' => now(),
        ]);

        $replyText = __('Thanks — I have logged your request.');

        $reply = ChatbotHistory::query()->create([
            'chatbot_id' => $chatbot->getKey(),
            'conversation_id' => $conversation->getKey(),
            'role' => 'assistant',
            'model' => $chatbot->ai_model,
            'message' => $replyText,
            'read_at' => now(),
        ]);

        return response()->json([
            'conversation_id' => $conversation->getKey(),
            'reply' => [
                'id' => $reply->getKey(),
                'message' => $reply->message,
            ],
        ]);
    }

    public function messages(Request $request, Chatbot $chatbot, string $sessionId, int $conversationId): JsonResponse
    {
        $chatbot = $this->portalChatbot($request, $chatbot);

        $messages = ChatbotHistory::query()
            ->where('chatbot_id', $chatbot->getKey())
            ->where('conversation_id', $conversationId)
            ->orderByDesc('id')
            ->paginate($request->integer('per_page', 20));

        return response()->json($messages);
    }

    public function file(Request $request, Chatbot $chatbot, string $sessionId, int $conversationId): JsonResponse
    {
        $chatbot = $this->portalChatbot($request, $chatbot);
        $validated = $request->validate([
            'media' => 'required|file|max:20480',
            'message' => 'nullable|string',
        ]);

        $path = $validated['media']->store('chatbot-media', 'public');

        $message = ChatbotHistory::query()->create([
            'chatbot_id' => $chatbot->getKey(),
            'conversation_id' => $conversationId,
            'role' => 'user',
            'model' => $chatbot->ai_model,
            'message' => $validated['message'] ?? '',
            'media_url' => Storage::url($path),
            'media_name' => $validated['media']->getClientOriginalName(),
            'read_at' => now(),
        ]);

        return response()->json([
            'ok' => true,
            'message' => $message,
        ], 201);
    }

    public function sendEmail(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'message' => 'required|string',
        ]);

        return response()->json([
            'ok' => true,
            'sent' => true,
            'email' => $validated['email'],
        ]);
    }

    public function review(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        return response()->json([
            'ok' => true,
            'review' => $validated,
        ], 201);
    }
}
