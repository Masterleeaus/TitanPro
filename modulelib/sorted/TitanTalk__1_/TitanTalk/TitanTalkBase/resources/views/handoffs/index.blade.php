@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">TitanTalk Handoffs</h3>
        <span class="text-muted">Human takeover queue for AI conversations</span>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Priority</th>
                        <th>Channel</th>
                        <th>Intent</th>
                        <th>Goal</th>
                        <th>Reason</th>
                        <th>State</th>
                        <th>Requested</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($handoffs as $handoff)
                        <tr>
                            <td>#{{ $handoff->id }}</td>
                            <td><span class="badge bg-{{ $handoff->priority === 'high' ? 'danger' : 'secondary' }}">{{ $handoff->priority }}</span></td>
                            <td>{{ $handoff->channel ?: 'unknown' }}</td>
                            <td>{{ $handoff->intent ?: 'general' }}</td>
                            <td>{{ $handoff->goal ?: 'n/a' }}</td>
                            <td style="max-width: 320px;">{{ $handoff->reason }}</td>
                            <td>{{ $handoff->state }}</td>
                            <td>{{ optional($handoff->requested_at)->diffForHumans() }}</td>
                            <td>
                                <form method="POST" action="{{ route('dashboard.user.titan-talk.handoffs.resolve', $handoff->id) }}" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-success">Resolve</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center text-muted py-4">No active handoffs. The goblins are calm.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $handoffs->links() }}</div>
</div>
@endsection
