<?php

namespace Modules\TitanEchoAssist\Http\Controllers\Api\Portal;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\TitanEchoAssist\Models\Chatbot;

class PortalWorkDataController extends PortalBaseController
{
    public function visits(Request $request, Chatbot $chatbot): JsonResponse
    {
        $chatbot = $this->portalChatbot($request, $chatbot);
        $table = $this->firstExistingTable(['service_jobs', 'jobs']);
        $visits = $table !== null ? $this->scopedQuery($chatbot, $table)?->paginate($request->integer('per_page', 10)) : null;

        return response()->json($visits ?? ['data' => []]);
    }

    public function invoices(Request $request, Chatbot $chatbot): JsonResponse
    {
        $chatbot = $this->portalChatbot($request, $chatbot);
        $invoices = $this->scopedQuery($chatbot, 'invoices')?->paginate($request->integer('per_page', 10));

        return response()->json($invoices ?? ['data' => []]);
    }

    public function documents(Request $request, Chatbot $chatbot): JsonResponse
    {
        $chatbot = $this->portalChatbot($request, $chatbot);
        $documents = $this->scopedQuery($chatbot, 'ext_chatbot_portal_documents')?->paginate($request->integer('per_page', 10));

        return response()->json($documents ?? ['data' => []]);
    }

    public function issues(Request $request, Chatbot $chatbot): JsonResponse
    {
        $chatbot = $this->portalChatbot($request, $chatbot);
        $issues = $this->scopedQuery($chatbot, 'tickets')?->paginate($request->integer('per_page', 10));

        return response()->json($issues ?? ['data' => []]);
    }

    public function checklists(Request $request, Chatbot $chatbot, int $id): JsonResponse
    {
        $chatbot = $this->portalChatbot($request, $chatbot);
        $items = $this->scopedQuery($chatbot, 'job_checklists')?->where('service_job_id', $id)?->get();

        return response()->json(['data' => $items ?? []]);
    }

    public function timeline(Request $request, Chatbot $chatbot, int $id): JsonResponse
    {
        $chatbot = $this->portalChatbot($request, $chatbot);
        $items = $this->scopedQuery($chatbot, 'service_job_timelines')?->where('service_job_id', $id)?->orderByDesc('id')?->get();

        return response()->json(['data' => $items ?? []]);
    }

    public function payInvoice(Request $request, Chatbot $chatbot, int $id): JsonResponse
    {
        $chatbot = $this->portalChatbot($request, $chatbot);

        $invoice = $this->scopedQuery($chatbot, 'invoices')?->where('id', $id)->first();

        return response()->json([
            'invoice_id' => $id,
            'payment_url' => is_object($invoice) ? ($invoice->payment_url ?? null) : null,
        ]);
    }
}
