<?php

declare(strict_types=1);

namespace App\Extensions\TitanLeads\System\Http\Controllers\Leads;

use App\Http\Controllers\Controller;
use App\Extensions\TitanLeads\System\Models\Leads\PcLead;
use App\Extensions\TitanLeads\System\Models\MarketingConversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MailboxController extends Controller
{
    public function show(int $lead)
    {
        $userId = (int) Auth::id();
        $lead = PcLead::query()->where('user_id', $userId)->findOrFail($lead);

        $conversations = MarketingConversation::query()
            ->where('lead_id', $lead->id)
            ->orderByDesc('updated_at')
            ->paginate(20);

        return view('titan-leads::leads.mailbox', compact('lead', 'conversations'));
    }

    public function linkConversation(Request $request, int $lead)
    {
        $userId = (int) Auth::id();
        $lead = PcLead::query()->where('user_id', $userId)->findOrFail($lead);

        $data = $request->validate([
            'conversation_id' => 'required|integer',
        ]);

        $conversation = MarketingConversation::query()->findOrFail((int) $data['conversation_id']);
        $conversation->lead_id = $lead->id;
        $conversation->save();

        return back()->with('success', 'Conversation linked to lead');
    }
}
