@extends('titantalk::voice.layouts.app')

@section('content')
    <div class="container py-4">
        <h3 class="mb-3">TitanTalk Knowledge</h3>
        <div class="row g-3 mb-4">
            <div class="col-md-4"><div class="card"><div class="card-body"><strong>Total items</strong><div>{{ $overview['total'] }}</div></div></div></div>
            <div class="col-md-4"><div class="card"><div class="card-body"><strong>Trained</strong><div>{{ $overview['trained'] }}</div></div></div></div>
            <div class="col-md-4"><div class="card"><div class="card-body"><strong>Pending</strong><div>{{ $overview['pending'] }}</div></div></div></div>
        </div>

        <div class="card mb-4">
            <div class="card-header">Knowledge types</div>
            <div class="card-body">
                @forelse($overview['by_type'] as $type => $count)
                    <span class="badge bg-secondary me-2 mb-2">{{ $type ?: 'unknown' }}: {{ $count }}</span>
                @empty
                    <p class="mb-0 text-muted">No knowledge items indexed yet.</p>
                @endforelse
            </div>
        </div>

        <div class="card">
            <div class="card-header">Recent knowledge items</div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Source</th>
                        <th>Trained</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($overview['recent'] as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->title ?: 'Untitled' }}</td>
                            <td>{{ $item->type?->value ?? $item->type ?? 'unknown' }}</td>
                            <td>{{ $item->url ?: ($item->file ?: 'inline') }}</td>
                            <td>{{ $item->trained_at ?: 'pending' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted">No indexed items yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
