@extends('panel.layout.app', ['disable_tblr' => true, 'has_sidebar' => true])

@section('title', 'Titan QC')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="mb-1">{{ $title ?? 'Titan QC' }}</h1>
            <p class="text-muted mb-0">{{ $subtitle ?? 'Inspection and proof surface.' }}</p>
        </div>
        <div class="d-flex gap-2">
            <a class="btn btn-primary" href="{{ route('dashboard.user.titanzero.api.preview.surface', ['surface' => 'qc']) }}">Surface JSON</a>
            <a class="btn btn-outline-primary" href="{{ route('dashboard.user.titanzero.go.index') }}">Open Go</a>
        </div>
    </div>

    @include('default.panel.user.titanzero.partials.surface-metrics', ['metrics' => $metrics ?? []])

    <div class="row g-3">
        <div class="col-lg-8">
            @include('default.panel.user.titanzero.partials.surface-jobs', ['jobs' => $jobs ?? [], 'heading' => 'QC Review Queue'])
        </div>
        <div class="col-lg-4">
            @include('default.panel.user.titanzero.partials.nav')
            @include('default.panel.user.titanzero.partials.pwa-panel', ['pwa' => $pwa ?? []])
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
