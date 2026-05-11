@extends('layouts.app')

@section('title', 'TitanTalk Analytics')

@section('content')
<div class="container py-4">
    <h3 class="mb-3">TitanTalk Analytics</h3>
    <p class="text-muted">Conversation health over the last {{ $analytics['window_days'] }} days.</p>

    <div class="row g-3 mb-4">
        <div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted small">Conversations</div><div class="fs-3">{{ $analytics['conversation_total'] }}</div></div></div></div>
        <div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted small">Messages</div><div class="fs-3">{{ $analytics['message_total'] }}</div></div></div></div>
        <div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted small">Open Handoffs</div><div class="fs-3">{{ $analytics['handoff_open'] }}</div></div></div></div>
        <div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted small">Total Handoffs</div><div class="fs-3">{{ $analytics['handoff_total'] }}</div></div></div></div>
    </div>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="card h-100"><div class="card-body">
                <h5 class="card-title">By Channel</h5>
                <ul class="mb-0">
                    @forelse($analytics['by_channel'] as $name => $count)
                        <li>{{ $name }} — {{ $count }}</li>
                    @empty
                        <li>No data yet.</li>
                    @endforelse
                </ul>
            </div></div>
        </div>
        <div class="col-md-4">
            <div class="card h-100"><div class="card-body">
                <h5 class="card-title">By State</h5>
                <ul class="mb-0">
                    @forelse($analytics['by_state'] as $name => $count)
                        <li>{{ $name }} — {{ $count }}</li>
                    @empty
                        <li>No data yet.</li>
                    @endforelse
                </ul>
            </div></div>
        </div>
        <div class="col-md-4">
            <div class="card h-100"><div class="card-body">
                <h5 class="card-title">By Intent</h5>
                <ul class="mb-0">
                    @forelse($analytics['by_intent'] as $name => $count)
                        <li>{{ $name }} — {{ $count }}</li>
                    @empty
                        <li>No data yet.</li>
                    @endforelse
                </ul>
            </div></div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">Recent Conversations</h5>
            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead><tr><th>ID</th><th>Name</th><th>Channel</th><th>Intent</th><th>State</th><th>Goal</th><th>Last Activity</th></tr></thead>
                    <tbody>
                    @forelse($analytics['recent_conversations'] as $item)
                        <tr>
                            <td>{{ $item['id'] }}</td>
                            <td>{{ $item['conversation_name'] }}</td>
                            <td>{{ $item['type'] }}</td>
                            <td>{{ $item['intent'] ?? '—' }}</td>
                            <td>{{ $item['state'] ?? '—' }}</td>
                            <td>{{ $item['goal'] ?? '—' }}</td>
                            <td>{{ $item['last_activity_at'] ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-muted">No recent conversations.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
