@extends('panel.layout.app', ['disable_tblr' => true, 'has_sidebar' => true])
@section('title', 'Titan Zero Audit')
@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1">Titan Zero Audit Timeline</h1>
            <p class="text-muted mb-0">Signal breadcrumbs for proposals, approvals, and other AI shenanigans.</p>
        </div>
        <a class="btn btn-outline-primary" href="{{ route('dashboard.user.titanzero.index') }}">Back to Zero</a>
    </div>
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    @include('default.panel.user.titanzero.partials.audit-list', ['audit' => $audit ?? []])
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            @include('default.panel.user.titanzero.partials.nav')
        </div>
    </div>
</div>
@endsection
