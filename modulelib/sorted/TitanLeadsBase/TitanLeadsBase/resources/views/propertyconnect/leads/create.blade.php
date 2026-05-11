@extends('panel.layout.app')

@section('title', 'Titan Leads - New Lead')

@section('content')
<div class="container py-4">
    <h3 class="mb-3">Create Lead</h3>

    <form method="post" action="{{ route('dashboard.user.titan-leads.leads.store') }}">
        @csrf

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Lead type</label>
                <input name="lead_type" class="form-control" placeholder="property_manager / strata / facilities / airbnb">
            </div>
            <div class="col-md-4">
                <label class="form-label">Company</label>
                <input name="company_name" class="form-control" placeholder="Agency / Strata firm / Facility company">
            </div>
            <div class="col-md-4">
                <label class="form-label">Person</label>
                <input name="person_name" class="form-control" placeholder="Contact name">
            </div>

            <div class="col-md-4">
                <label class="form-label">Email</label>
                <input name="email" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label">Phone</label>
                <input name="phone" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label">Source</label>
                <input name="source" class="form-control" placeholder="built-in pack / import / manual">
            </div>

            <div class="col-md-6">
                <label class="form-label">Pipeline</label>
                <select name="pipeline_id" class="form-select" required>
                    @foreach($pipelines as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
                @if($pipelines->isEmpty())
                    <div class="text-muted small mt-1">No pipelines yet — seed defaults after install.</div>
                @endif
            </div>

            <div class="col-md-6">
                <label class="form-label">Stage</label>
                <select name="stage_id" class="form-select" required>
                    @foreach($stages as $s)
                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                    @endforeach
                </select>
                @if($stages->isEmpty())
                    <div class="text-muted small mt-1">No stages yet — seed defaults after install.</div>
                @endif
            </div>

            <div class="col-12">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-control" rows="4"></textarea>
            </div>

            <div class="col-12 d-flex gap-2">
                <button class="btn btn-primary">Create</button>
                <a href="{{ route('dashboard.user.titan-leads.leads.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection
