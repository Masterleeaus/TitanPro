@extends('panel.layout.app')

@section('title', 'Titan Leads - Lead')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">{{ $lead->company_name ?? 'Lead' }}</h3>
        <a class="btn btn-primary" href="{{ route('dashboard.user.titan-leads.leads.mailbox', $lead->id) }}">Open Mailbox</a>
    </div>

    <div class="card">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Person</dt><dd class="col-sm-9">{{ $lead->person_name ?? '-' }}</dd>
                <dt class="col-sm-3">Type</dt><dd class="col-sm-9">{{ $lead->lead_type ?? '-' }}</dd>
                <dt class="col-sm-3">Email</dt><dd class="col-sm-9">{{ $lead->email ?? '-' }}</dd>
                <dt class="col-sm-3">Phone</dt><dd class="col-sm-9">{{ $lead->phone ?? '-' }}</dd>
                <dt class="col-sm-3">Source</dt><dd class="col-sm-9">{{ $lead->source ?? '-' }}</dd>
                <dt class="col-sm-3">Notes</dt><dd class="col-sm-9">{!! nl2br(e($lead->notes ?? '')) !!}</dd>
            </dl>
        </div>
    </div>
</div>
@endsection
