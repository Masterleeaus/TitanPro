@extends('panel.layout.app')
@section('title', 'Titan Leads - Leads')

@section('content')
<div class="container-xl py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h2 class="mb-0">Leads</h2>
      <div class="text-muted">PropertyConnect-style pipeline leads inside Titan Leads.</div>
    </div>
    <div class="d-flex gap-2">
      <form method="GET" class="d-flex gap-2">
        <input class="form-control" name="q" value="{{ $q }}" placeholder="Search leads...">
        <button class="btn btn-outline-primary" type="submit">Search</button>
      </form>
      <a class="btn btn-primary" href="{{ route('dashboard.user.titan-leads.leads.create') }}">New Lead</a>
    </div>
  </div>

  <div class="card">
    <div class="table-responsive">
      <table class="table table-vcenter">
        <thead>
          <tr>
            <th>Company</th>
            <th>Person</th>
            <th>Type</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Next follow-up</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @forelse($leads as $lead)
            <tr>
              <td>{{ $lead->company_name ?? '-' }}</td>
              <td>{{ $lead->person_name ?? '-' }}</td>
              <td>{{ $lead->lead_type ?? '-' }}</td>
              <td>{{ $lead->phone ?? '-' }}</td>
              <td>{{ $lead->email ?? '-' }}</td>
              <td>{{ optional($lead->next_followup_at)->format('Y-m-d') ?? '-' }}</td>
              <td class="text-end">
                <a class="btn btn-sm btn-outline-primary" href="{{ route('dashboard.user.titan-leads.leads.show', $lead->id) }}">Open</a>
              </td>
            </tr>
          @empty
            <tr><td colspan="7" class="text-center text-muted py-4">No leads yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="card-footer">
      {{ $leads->links() }}
    </div>
  </div>
</div>
@endsection
