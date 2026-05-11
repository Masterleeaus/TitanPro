@extends('panel.layout.app', ['disable_tblr' => true, 'has_sidebar' => true])
@section('title', 'Titan Zero Proposals')
@section('content')
<div class="container py-4">
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('warning'))<div class="alert alert-warning">{{ session('warning') }}</div>@endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1">Titan Zero Proposals</h1>
            <p class="text-muted mb-0">Approval-first queue for AI suggestions before they touch anything expensive or embarrassing.</p>
        </div>
        <a class="btn btn-outline-primary" href="{{ route('dashboard.user.titanzero.index') }}">Back to Zero</a>
    </div>
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    @include('default.panel.user.titanzero.partials.proposal-table', ['proposals' => $proposals ?? [], 'interactive' => true])
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            @include('default.panel.user.titanzero.partials.nav')
        </div>
    </div>
</div>
@endsection
