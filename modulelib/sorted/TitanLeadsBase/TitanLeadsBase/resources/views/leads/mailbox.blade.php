@extends('panel.layout.app')
@section('title', 'Titan Leads - Lead Mailbox')

@section('content')
<div class="container-xl py-4">
  <div class="d-flex justify-content-between align-items-start mb-3">
    <div>
      <h2 class="mb-0">Mailbox · {{ $lead->company_name ?? 'Lead' }}</h2>
      <div class="text-muted">Linked omnichannel conversations for this lead.</div>
    </div>
    <div class="d-flex gap-2">
      <a class="btn btn-outline-secondary" href="{{ route('dashboard.user.titan-leads.leads.show', $lead->id) }}">Back</a>
      <a class="btn btn-outline-primary" href="{{ route('dashboard.user.titan-leads.inbox.index') }}">Open Inbox</a>
    </div>
  </div>

  <div class="card">
    <div class="table-responsive">
      <table class="table table-vcenter">
        <thead>
          <tr>
            <th>Channel</th>
            <th>From</th>
            <th>To</th>
            <th>Status</th>
            <th>Updated</th>
          </tr>
        </thead>
        <tbody>
          @forelse($conversations as $c)
            <tr>
              <td>{{ $c->type }}</td>
              <td>{{ $c->from_id }}</td>
              <td>{{ $c->to_id }}</td>
              <td>{{ $c->status }}</td>
              <td>{{ $c->updated_at?->format('Y-m-d H:i') }}</td>
            </tr>
          @empty
            <tr><td colspan="5" class="text-center text-muted py-4">No conversations linked yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="card-footer">{{ $conversations->links() }}</div>
  </div>

  <div class="mt-4 card">
    <div class="card-header"><strong>Link an existing conversation</strong></div>
    <div class="card-body">
      <form method="POST" action="{{ route('dashboard.user.titan-leads.leads.mailbox.link', $lead->id) }}">
        @csrf
        <div class="d-flex gap-2">
          <input class="form-control" name="conversation_id" placeholder="Conversation ID">
          <button class="btn btn-primary" type="submit">Link</button>
        </div>
        <div class="text-muted mt-2">Tip: open Inbox and use the conversation ID from the URL or developer tools.</div>
      </form>
    </div>
  </div>
</div>
@endsection
