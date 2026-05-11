<?php

namespace App\Extensions\MarketingBot\System\Http\Controllers\Voice\Calls;

use Illuminate\Routing\Controller;
use App\Extensions\MarketingBot\System\Http\Requests\Voice\AssignCallRequest;
use App\Extensions\MarketingBot\System\Http\Requests\Voice\CallbackCallRequest;
use App\Extensions\MarketingBot\System\Http\Requests\Voice\DispositionCallRequest;
use App\Extensions\MarketingBot\System\Http\Requests\Voice\NoteCallRequest;
use App\Extensions\MarketingBot\System\Models\Voice\Call;
use App\Extensions\MarketingBot\System\Models\Voice\CallNote;

class CallActionsController extends Controller
{
    public function assign($id, AssignCallRequest $request)
    {
        $call = Call::query()->findOrFail($id);
        $call->assigned_to_user_id = $request->input('assigned_to_user_id');
        $call->assigned_at = now();
        $call->save();

        return redirect()->route('dashboard.user.marketing-bot.voice.calls.show', $call->id)->with('success', 'Assigned.');
    }

    public function setDisposition($id, DispositionCallRequest $request)
    {
        $call = Call::query()->findOrFail($id);
        $call->disposition = $request->input('disposition');
        $call->disposition_notes = $request->input('disposition_notes');
        if ($request->filled('priority')) {
            $call->priority = (int) $request->input('priority');
        }
        $call->save();

        return redirect()->route('dashboard.user.marketing-bot.voice.calls.show', $call->id)->with('success', 'Updated.');
    }

    public function setCallback($id, CallbackCallRequest $request)
    {
        $call = Call::query()->findOrFail($id);
        $call->callback_due_at = $request->input('callback_due_at');
        $call->save();

        return redirect()->route('dashboard.user.marketing-bot.voice.calls.show', $call->id)->with('success', 'Callback scheduled.');
    }

    public function addNote($id, NoteCallRequest $request)
    {
        $call = Call::query()->findOrFail($id);

        CallNote::create([
            'call_id' => $call->id,
            'user_id' => auth()->id(),
            'note' => $request->input('note'),
        ]);

        return redirect()->route('dashboard.user.marketing-bot.voice.calls.show', $call->id)->with('success', 'Note added.');
    }
}
