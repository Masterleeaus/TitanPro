@extends('panel.layout.app', ['disable_tblr' => true, 'has_sidebar' => true])

@section('title', 'Titan Boss')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="mb-1">{{ $title ?? 'Titan Boss' }}</h1>
            <p class="text-muted mb-0">{{ $subtitle ?? 'Owner command centre.' }}</p>
        </div>
        <div class="d-flex gap-2">
            <a class="btn btn-primary" href="{{ route('dashboard.user.titanzero.proposals.index') }}">Review Proposals</a>
            <a class="btn btn-outline-primary" href="{{ route('dashboard.user.titanzero.audit.index') }}">Open Audit</a>
        </div>
    </div>

    @include('default.panel.user.titanzero.partials.surface-metrics', ['metrics' => $metrics ?? []])

    <div class="row g-3">
        <div class="col-lg-8">
            @include('default.panel.user.titanzero.partials.surface-jobs', ['jobs' => $jobs ?? [], 'heading' => 'Operational Queue'])
        </div>
        <div class="col-lg-4">
            @include('default.panel.user.titanzero.partials.nav')
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white"><strong>Plugins Available</strong></div>
                <div class="list-group list-group-flush">
                    @forelse(($plugins ?? []) as $plugin)
                        <div class="list-group-item">
                            <div class="fw-semibold">{{ $plugin['label'] ?? $plugin['key'] ?? 'Plugin' }}</div>
                            <div class="text-muted small">{{ $plugin['description'] ?? 'Registered in Titan Zero plugin registry.' }}</div>
                        </div>
                    @empty
                        <div class="list-group-item text-muted">No plugins registered.</div>
                    @endforelse
                </div>
            </div>
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white"><strong>Recent Audit</strong></div>
                <div class="card-body p-0">
                    @include('default.panel.user.titanzero.partials.audit-list', ['audit' => $audit ?? []])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
