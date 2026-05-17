@extends('default.panel.user.layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-12">
                <h4 class="mb-1">Embed</h4>
                <p class="text-muted mb-0">
                    Use the code below to embed this titan_operator frame on your site.
                </p>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <strong>Embed Code</strong>
                    </div>
                    <div class="card-body">
                        <textarea class="form-control" rows="8" readonly>{{ $embedCode }}</textarea>
                        <div class="mt-2">
                            <small class="text-muted">Frame URL: {{ $iframeUrl }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <strong>Preview</strong>
                    </div>
                    <div class="card-body" style="min-height: 420px;">
                        {!! $embedCode !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
