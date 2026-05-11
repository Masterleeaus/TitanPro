@extends('panel.layout.app')

@section('title', 'Titan Leads - Leads')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Leads</h3>
        <a href="{{ route('dashboard.user.titan-leads.leads.create') }}" class="btn btn-primary">New Lead</a>
    </div>

    <form method="get" class="mb-3">
        <div class="input-group">
            <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Search company, person, email, phone">
            <button class="btn btn-outline-secondary" type="submit">Search</button>
        </div>
    </form>

    <div class="card">
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Company</th>
                        <th>Person</th>
                        <th>Type</th>
                        <th>Email</th>
                        <th>Phone</th>
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
                        <td>{{ $lead->email ?? '-' }}</td>
                        <td>{{ $lead->phone ?? '-' }}</td>
                        <td>{{ $lead->next_followup_at ? $lead->next_followup_at->format('Y-m-d H:i') : '-' }}</td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('dashboard.user.titan-leads.leads.show', $lead->id) }}">Open</a>
                            <a class="btn btn-sm btn-primary" href="{{ route('dashboard.user.titan-leads.leads.mailbox', $lead->id) }}">Mailbox</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-4">No leads yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $leads->links() }}
    </div>
</div>
@endsection
