<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <strong>{{ $heading ?? 'Jobs' }}</strong>
        <span class="text-muted small">{{ count($jobs ?? []) }} shown</span>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Job</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Customer</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse(($jobs ?? []) as $job)
                    <tr>
                        <td>#{{ $job['id'] ?? '—' }} · {{ $job['title'] ?? 'Untitled job' }}</td>
                        <td><span class="badge bg-light text-dark">{{ strtoupper($job['status'] ?? 'unknown') }}</span></td>
                        <td>{{ strtoupper($job['priority'] ?? 'normal') }}</td>
                        <td>{{ $job['customer_name'] ?? 'Unlinked' }}</td>
                        <td>{{ $job['service_date'] ?? ($job['scheduled_start_at'] ?? '—') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">No matching jobs yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
