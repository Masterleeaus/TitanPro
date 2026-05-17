@php($interactive = $interactive ?? false)
@if(empty($proposals))
    <div class="p-4 text-muted">No Titan Zero proposals yet. Create tables and hit the API think/chat endpoints to start the mischief.</div>
@else
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Intent</th>
                    <th>Risk</th>
                    <th>Status</th>
                    <th>Updated</th>
                    @if($interactive)<th style="min-width: 320px;">Review</th>@endif
                </tr>
            </thead>
            <tbody>
                @foreach($proposals as $proposal)
                    @php($status = $proposal['status'] ?? 'pending_review')
                    <tr>
                        <td>{{ $proposal['id'] ?? '—' }}</td>
                        <td>
                            <div class="fw-semibold">{{ $proposal['intent'] ?? '—' }}</div>
                            @if(!empty($proposal['review_notes']))
                                <div class="small text-muted">Notes: {{ $proposal['review_notes'] }}</div>
                            @endif
                        </td>
                        <td><span class="badge bg-light text-dark border">{{ strtoupper($proposal['risk'] ?? 'low') }}</span></td>
                        <td>
                            <span class="badge {{ in_array($status, ['approved']) ? 'bg-success' : (in_array($status, ['rejected']) ? 'bg-danger' : (in_array($status, ['modified','deferred']) ? 'bg-warning text-dark' : 'bg-secondary')) }}">
                                {{ $status }}
                            </span>
                        </td>
                        <td>{{ $proposal['reviewed_at'] ?? $proposal['updated_at'] ?? $proposal['created_at'] ?? '—' }}</td>
                        @if($interactive)
                            <td>
                                @if(!empty($proposal['id']))
                                <form method="POST" action="{{ route('dashboard.user.titanzero.proposals.update', $proposal['id']) }}" class="d-grid gap-2">
                                    @csrf
                                    <div class="d-flex gap-2">
                                        <select class="form-select form-select-sm" name="status">
                                            @foreach(['pending_review', 'approved', 'rejected', 'modified', 'deferred'] as $option)
                                                <option value="{{ $option }}" @selected($status === $option)>{{ $option }}</option>
                                            @endforeach
                                        </select>
                                        <button class="btn btn-sm btn-primary" type="submit">Save</button>
                                    </div>
                                    <textarea class="form-control form-control-sm" name="notes" rows="2" placeholder="Optional review notes...">{{ $proposal['review_notes'] ?? '' }}</textarea>
                                </form>
                                @else
                                    <span class="text-muted small">Create tables first</span>
                                @endif
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
