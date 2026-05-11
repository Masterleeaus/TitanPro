@extends('panel.layout.app')

@section('title', 'Titan Leads - Mailbox')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-2 px-2">
        <div>
            <h4 class="mb-0">{{ $lead->company_name ?? 'Lead Mailbox' }}</h4>
            <div class="text-muted small">{{ $lead->person_name }} • {{ $lead->email }} • {{ $lead->phone }}</div>
        </div>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-secondary" href="{{ route('dashboard.user.titan-leads.leads.show', $lead->id) }}">Lead</a>
            <a class="btn btn-outline-primary" href="{{ route('dashboard.user.titan-leads.inbox.index') }}">Full Inbox</a>
        </div>
    </div>

    <div class="row g-0">
        <div class="col-md-3 border-end" style="height: calc(100vh - 140px); overflow:auto;">
            <div class="p-2">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong>Threads</strong>
                </div>

                @forelse($conversations as $c)
                    <a class="d-block p-2 rounded mb-1 {{ $activeConversation && $activeConversation->id === $c->id ? 'bg-light' : '' }}"
                       href="{{ route('dashboard.user.titan-leads.leads.mailbox', $lead->id) }}?conversation_id={{ $c->id }}">
                        <div class="small text-muted">{{ strtoupper($c->type ?? 'msg') }}</div>
                        <div class="fw-semibold">{{ $c->conversation_name ?? ('Conversation #'.$c->id) }}</div>
                    </a>
                @empty
                    <div class="text-muted small p-2">No conversations linked yet.</div>
                    <div class="text-muted small p-2">Tip: open the main Inbox, find the thread, then link it here (Pass v1.3 improves this).</div>
                @endforelse
            </div>
        </div>

        <div class="col-md-9" style="height: calc(100vh - 140px); overflow:auto;">
            <div class="p-3">
                @if($activeConversation)
                    <div class="mb-2">
                        <div class="text-muted small">Active Thread</div>
                        <div class="fw-semibold">{{ $activeConversation->conversation_name ?? ('Conversation #'.$activeConversation->id) }}</div>
                    </div>

                    <div class="card">
                        <div class="card-body" style="max-height: 60vh; overflow:auto;">
                            @foreach($messages as $m)
                                <div class="mb-3">
                                    <div class="small text-muted">{{ $m->created_at }}</div>
                                    <div>{{ $m->message }}</div>
                                </div>
                            @endforeach
                            @if($messages->isEmpty())
                                <div class="text-muted">No messages yet.</div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-3">
                        <a class="btn btn-primary" href="{{ route('dashboard.user.titan-leads.inbox.index') }}">Reply / Send (from Inbox)</a>
                        <div class="text-muted small mt-1">
                            MVP: messaging UI stays in the core Inbox. Next pass will embed the composer here.
                        </div>
                    </div>
                @else
                    <div class="text-muted">No active conversation selected.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
