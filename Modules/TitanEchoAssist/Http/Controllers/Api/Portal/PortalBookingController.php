<?php

namespace Modules\TitanEchoAssist\Http\Controllers\Api\Portal;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\TitanEchoAssist\Jobs\NotifyPortalBookingRequestJob;
use Modules\TitanEchoAssist\Models\Chatbot;
use Modules\TitanEchoAssist\Models\ChatbotPortalBookingRequest;

class PortalBookingController extends PortalBaseController
{
    public function index(Request $request, Chatbot $chatbot): JsonResponse
    {
        $chatbot = $this->portalChatbot($request, $chatbot);
        $bookings = ChatbotPortalBookingRequest::query()
            ->where('chatbot_id', $chatbot->getKey())
            ->when($chatbot->company_id !== null, fn ($query) => $query->where('company_id', $chatbot->company_id))
            ->latest('id')
            ->paginate($request->integer('per_page', 10));

        return response()->json($bookings);
    }

    public function store(Request $request, Chatbot $chatbot): JsonResponse
    {
        $chatbot = $this->portalChatbot($request, $chatbot);

        $validated = $request->validate([
            'service_type' => 'required|string|max:120',
            'requested_date' => 'nullable|date',
            'requested_time' => 'nullable|string|max:40',
            'preferred_date' => 'nullable|date',
            'preferred_time' => 'nullable|string|max:40',
            'notes' => 'nullable|string|max:1000',
            'customer_id' => 'nullable|integer',
        ]);

        $booking = ChatbotPortalBookingRequest::query()->create([
            'chatbot_id' => $chatbot->getKey(),
            'company_id' => $chatbot->company_id,
            'customer_id' => $validated['customer_id'] ?? null,
            'session_id' => (string) $request->route('sessionId', ''),
            'requested_at' => now(),
            'service_type' => $validated['service_type'],
            'preferred_date' => $validated['preferred_date'] ?? $validated['requested_date'] ?? null,
            'preferred_time' => $validated['preferred_time'] ?? $validated['requested_time'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
        ]);

        NotifyPortalBookingRequestJob::dispatch($booking->getKey());

        return response()->json([
            'ok' => true,
            'booking_request' => $booking,
            'notification_queued' => true,
        ], 201);
    }
}
