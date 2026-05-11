@if(empty($audit))
    <div class="p-4 text-muted">No audit records yet. That means either perfect calm or the SQL tables are not in place yet.</div>
@else
    <div class="list-group list-group-flush">
        @foreach($audit as $entry)
            <div class="list-group-item">
                <div class="d-flex justify-content-between gap-3">
                    <div>
                        <div class="fw-semibold">{{ $entry['event_key'] ?? 'event' }}</div>
                        <div class="small text-muted">{{ $entry['meta_json'] ?? '' }}</div>
                    </div>
                    <div class="small text-muted text-end">{{ $entry['created_at'] ?? '—' }}</div>
                </div>
            </div>
        @endforeach
    </div>
@endif
