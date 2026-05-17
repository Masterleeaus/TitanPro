<?php

declare(strict_types=1);

namespace App\Extensions\TitanLeads\System\Http\Controllers\PropertyConnect;

use App\Http\Controllers\Controller;
use App\Extensions\TitanLeads\System\Models\Leads\PcLead;
use App\Extensions\TitanLeads\System\Models\MarketingConversation;
use App\Extensions\TitanLeads\System\Models\MarketingMessageHistory;
use Illuminate\Http\Request;

class MailboxController extends Controller
{
    public function show(Request $request, int $leadId)
    {
        $userId = (int)auth()->id();

        $lead = PcLead::query()
            ->where('user_id', $userId)
            ->findOrFail($leadId);

        $conversationId = (int)$request->get('conversation_id', 0);

        $conversations = MarketingConversation::query()
            ->where('user_id', $userId)
            ->where('lead_id', $lead->id)
            ->orderByDesc('updated_at')
            ->get();

        $activeConversation = null;
        $messages = collect();

        if ($conversationId) {
            $activeConversation = $conversations->firstWhere('id', $conversationId);
        }
        if (!$activeConversation) {
            $activeConversation = $conversations->first();
        }

        if ($activeConversation) {
            $messages = MarketingMessageHistory::query()
                ->where('user_id', $userId)
                ->where('conversation_id', $activeConversation->id)
                ->orderBy('created_at')
                ->get();
        }

        return view('titan-leads::titan-leads.mailbox.show', [
            'lead' => $lead,
            'conversations' => $conversations,
            'activeConversation' => $activeConversation,
            'messages' => $messages,
        ]);
    }

    public function linkConversation(Request $request, int $leadId)
    {
        $userId = (int)auth()->id();

        $lead = PcLead::query()
            ->where('user_id', $userId)
            ->findOrFail($leadId);

        $data = $request->validate([
            'conversation_id' => 'required|integer',
        ]);

        MarketingConversation::query()
            ->where('user_id', $userId)
            ->where('id', (int)$data['conversation_id'])
            ->update(['lead_id' => $lead->id]);

        return back()->with('success', 'Conversation linked to lead.');
    }
}
