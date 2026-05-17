@extends('panel.layout.app', ['disable_tblr' => true, 'has_sidebar' => true])

@section('title', 'Titan Zero')

@section('content')
<div class="container py-4">
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('warning'))<div class="alert alert-warning">{{ session('warning') }}</div>@endif

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="mb-1">Titan Zero Core</h1>
            <p class="text-muted mb-0">Reasoning, governance, proposals, and audit stitched into the host site instead of floating around like haunted extension debris.</p>
        </div>
        <div class="d-flex gap-2">
            <a class="btn btn-primary" href="{{ route('dashboard.user.titanzero.proposals.index') }}">Proposal Queue</a>
            <a class="btn btn-outline-primary" href="{{ route('dashboard.user.titanzero.audit.index') }}">Audit Timeline</a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        @foreach(($readiness ?? []) as $key => $score)
            <div class="col-6 col-lg-2">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-uppercase text-muted small">{{ str($key)->replace('_', ' ')->title() }}</div>
                        <div class="display-6 fw-bold">{{ $score }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white"><strong>Signal Envelope</strong></div>
                <div class="card-body">
                    <pre class="small mb-0" style="white-space: pre-wrap;">{{ json_encode($envelope ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                </div>
            </div>
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white"><strong>Recent Proposals</strong></div>
                <div class="card-body p-0">
                    @include('default.panel.user.titanzero.partials.proposal-table', ['proposals' => $proposals ?? []])
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            @include('default.panel.user.titanzero.partials.nav')
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white"><strong>Capability Surface</strong></div>
                <div class="card-body">
                    @foreach(($tools ?? []) as $tool)
                        <div class="border rounded p-3 mb-2">
                            <div class="fw-semibold">{{ $tool['label'] }}</div>
                            <div class="text-muted small">{{ strtoupper($tool['group']) }} · risk {{ strtoupper($tool['risk']) }}</div>
                            <div class="small">Route: {{ $tool['route'] }}</div>
                        </div>
                    @endforeach
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
