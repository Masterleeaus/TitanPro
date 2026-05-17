@extends('panel.layout.app')
@section('title', 'Titan Leads - New Lead')

@section('content')
<div class="container-xl py-4">
  <h2 class="mb-3">Create Lead</h2>

  <div class="card">
    <div class="card-body">
      <form method="POST" action="{{ route('dashboard.user.titan-leads.leads.store') }}">
        @csrf
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Lead type</label>
            <select class="form-select" name="lead_type">
              <option value="property_manager">Property Manager</option>
              <option value="strata">Strata Manager</option>
              <option value="facilities">Facilities Manager</option>
              <option value="airbnb">Short-stay Manager</option>
              <option value="real_estate">Real Estate Agency</option>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Company name</label>
            <input class="form-control" name="company_name" value="{{ old('company_name') }}">
          </div>
          <div class="col-md-4">
            <label class="form-label">Person name</label>
            <input class="form-control" name="person_name" value="{{ old('person_name') }}">
          </div>
          <div class="col-md-4">
            <label class="form-label">Phone</label>
            <input class="form-control" name="phone" value="{{ old('phone') }}">
          </div>
          <div class="col-md-4">
            <label class="form-label">Email</label>
            <input class="form-control" name="email" value="{{ old('email') }}">
          </div>
          <div class="col-md-4">
            <label class="form-label">Next follow-up</label>
            <input type="date" class="form-control" name="next_followup_at" value="{{ old('next_followup_at') }}">
          </div>

          <div class="col-md-6">
            <label class="form-label">Pipeline</label>
            <select class="form-select" name="pipeline_id">
              @foreach($pipelines as $p)
                <option value="{{ $p->id }}">{{ $p->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Stage</label>
            <select class="form-select" name="stage_id">
              @foreach($stages as $s)
                <option value="{{ $s->id }}">{{ $s->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-12">
            <label class="form-label">Notes</label>
            <textarea class="form-control" rows="4" name="notes">{{ old('notes') }}</textarea>
          </div>
        </div>

        <div class="mt-3 d-flex gap-2">
          <button class="btn btn-primary" type="submit">Create</button>
          <a class="btn btn-outline-secondary" href="{{ route('dashboard.user.titan-leads.leads.index') }}">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
