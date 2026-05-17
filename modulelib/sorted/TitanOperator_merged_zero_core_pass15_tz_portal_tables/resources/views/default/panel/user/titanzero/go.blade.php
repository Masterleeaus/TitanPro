@extends('panel.layout.app', ['disable_tblr' => true, 'has_sidebar' => true])

@section('title', 'Titan Go')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="mb-1">{{ $title ?? 'Titan Go' }}</h1>
            <p class="text-muted mb-0">{{ $subtitle ?? 'Field execution surface.' }}</p>
        </div>
        <div class="d-flex gap-2">
            <a class="btn btn-primary" href="{{ route('dashboard.user.titanzero.api.preview.surface', ['surface' => 'go']) }}">Surface JSON</a>
            <a class="btn btn-outline-primary" href="{{ route('dashboard.user.titanzero.index') }}">Back to Zero</a>
        </div>
    </div>

    @include('default.panel.user.titanzero.partials.surface-metrics', ['metrics' => $metrics ?? []])

    <div class="row g-3">
        <div class="col-lg-8">
            @include('default.panel.user.titanzero.partials.surface-jobs', ['jobs' => $jobs ?? [], 'heading' => 'My Assigned Jobs'])
        </div>
        <div class="col-lg-4">
            @include('default.panel.user.titanzero.partials.nav')
            @include('default.panel.user.titanzero.partials.surface-transcripts', ['voice_transcripts' => $voice_transcripts ?? []])
        </div>
    </div>
</div>
@endsection
