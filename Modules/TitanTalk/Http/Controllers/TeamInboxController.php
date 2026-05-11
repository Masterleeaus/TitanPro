<?php

namespace Modules\TitanTalk\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Modules\TitanTalk\Models\Conversation;
use Modules\TitanTalk\Services\ConversationThreadService;

class TeamInboxController extends Controller
{
    public function index(Request $request): Response
    {
        $conversations = Conversation::query()
            ->with(['messages' => fn ($query) => $query->latest('id')->limit(20)])
            ->latest('id')
            ->limit(50)
            ->get();

        return Inertia::render('Platform/TitanTalkInbox', [
            'conversations' => $conversations,
            'graphqlEndpoint' => url('/graphql'),
            'graphqlSchema' => 'titantalk',
        ]);
    }

    public function reply(Request $request, Conversation $conversation, ConversationThreadService $threadService): JsonResponse
    {
        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        $message = $threadService->recordOutbound(
            $conversation,
            $validated['message'],
            ['origin' => 'team-inbox', 'dispatched_channel' => $conversation->channel]
        );

        return response()->json([
            'ok' => true,
            'conversation_id' => $conversation->id,
            'message_id' => $message->id,
            'channel' => $conversation->channel,
        ]);
    }
}

