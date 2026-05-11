<form method="POST" action="{{ route('dashboard.user.marketing-bot.voice.calls.notes', $call->id) }}">
    @csrf
    <label class="form-label">Add note</label>
    <textarea name="note" class="form-control" rows="3" placeholder="Call notes..."></textarea>
    <div class="mt-2">
        <button class="btn btn-primary" type="submit">Add note</button>
    </div>
</form>