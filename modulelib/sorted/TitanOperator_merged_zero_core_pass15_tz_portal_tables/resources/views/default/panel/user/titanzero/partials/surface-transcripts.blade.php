<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><strong>Recent Voice Transcripts</strong></div>
    <div class="list-group list-group-flush">
        @forelse(($voice_transcripts ?? []) as $transcript)
            <div class="list-group-item">
                <div class="fw-semibold">{{ strtoupper($transcript['entity_type'] ?? 'command') }} #{{ $transcript['entity_id'] ?? '—' }}</div>
                <div class="text-muted small">{{ strtoupper($transcript['provider'] ?? 'unknown') }} · {{ strtoupper($transcript['status'] ?? 'staged') }} · {{ $transcript['transcribed_at'] ?? 'pending' }}</div>
            </div>
        @empty
            <div class="list-group-item text-muted">No recent voice transcripts found for this operator.</div>
        @endforelse
    </div>
</div>
