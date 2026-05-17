@extends('panel.layout.app')
@section('title', 'Titan Leads - Lead')

@section('content')
<div class="container-xl py-4">
  <div class="d-flex justify-content-between align-items-start mb-3">
    <div>
      <h2 class="mb-0">{{ $lead->company_name ?? 'Lead' }}</h2>
      <div class="text-muted">{{ $lead->person_name }} · {{ $lead->lead_type }}</div>
    </div>
    <div class="d-flex gap-2">
      <a class="btn btn-outline-primary" href="{{ route('dashboard.user.titan-leads.leads.mailbox', $lead->id) }}">Mailbox</a>
      <a class="btn btn-outline-secondary" href="{{ route('dashboard.user.titan-leads.leads.index') }}">Back</a>
    </div>
  </div>

  <div class="row g-3">
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <div class="mb-2"><strong>Phone:</strong> {{ $lead->phone ?? '-' }}</div>
          <div class="mb-2"><strong>Email:</strong> {{ $lead->email ?? '-' }}</div>
          <div class="mb-2"><strong>Next follow-up:</strong> {{ optional($lead->next_followup_at)->format('Y-m-d') ?? '-' }}</div>
          <div class="mb-2"><strong>Source:</strong> {{ $lead->source ?? '-' }}</div>
        </div>
      </div>
    </div>
    <div class="col-md-8">
      <div class="card">
        <div class="card-header"><strong>Notes</strong></div>
        <div class="card-body">
          <div class="text-muted" style="white-space: pre-wrap">{{ $lead->notes ?? '—' }}</div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
