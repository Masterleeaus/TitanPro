<?php

namespace Modules\TitanEchoAssist\Http\Controllers\Api\Portal;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\TitanEchoAssist\Models\Chatbot;

class PortalDocumentController extends PortalBaseController
{
    public function store(Request $request, Chatbot $chatbot): JsonResponse
    {
        $validated = $request->validate([
            'document' => 'required|file|max:20480',
            'customer_id' => 'nullable|integer',
            'title' => 'nullable|string|max:255',
        ]);

        $path = $validated['document']->store('chatbot-portal-documents', 'public');

        return response()->json([
            'ok' => true,
            'chatbot_id' => $chatbot->getKey(),
            'path' => '/uploads/' . $path,
            'title' => $validated['title'] ?? $validated['document']->getClientOriginalName(),
        ], 201);
    }

    public function index(Request $request, Chatbot $chatbot, int $customerId): JsonResponse
    {
        $chatbot = $this->portalChatbot($request, $chatbot);

        $documents = $this->companyScopedByCustomer($chatbot, 'ext_chatbot_portal_documents', $customerId)?->orderByDesc('id')?->paginate($request->integer('per_page', 20));

        return response()->json($documents ?? ['data' => []]);
    }
}
