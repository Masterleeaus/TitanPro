@extends('layouts.app')

@section('page-title')
    {{ __('Titan Docs Generator') }}
@endsection

@section('content')
@php
    $data = $wizard->payload_json ?? [];
    $s1 = (array) data_get($data, 'step_1', []);
    $s2 = (array) data_get($data, 'step_2', []);
    $s3 = (array) data_get($data, 'step_3', []);
    $s4 = (array) data_get($data, 'step_4', []);
    $suggestions = (array) data_get($s3, 'standards_suggestions', []);
@endphp

<div class="container-fluid">
    <div class="card mb-3">
        <div class="card-body">
            <h4 class="mb-1">{{ __('Review & Complete') }}</h4>
            <p class="text-muted mb-0">{{ __('Confirm details. You will choose a template after this step to generate the final output.') }}</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="mb-3">{{ __('Wizard Summary') }}</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="text-muted small">{{ __('Kind') }}</div>
                            <div class="fw-semibold">{{ $wizard->doc_kind === 'swms' ? __('SWMS') : __('Document') }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="text-muted small">{{ __('Document Type') }}</div>
                            <div class="fw-semibold">{{ data_get($s1, 'doc_type', __('(Not set)')) }}</div>
                        </div>
                    </div>

                    <hr>

                    <h6 class="mb-2">{{ __('Job / Context') }}</h6>
                    <div class="text-muted small mb-2">{{ __('Trade') }}: <span class="text-body">{{ data_get($s2, 'trade', '-') }}</span></div>
                    <div class="text-muted small mb-2">{{ __('Site / Project') }}: <span class="text-body">{{ data_get($s2, 'site_context', '-') }}</span></div>
                    <div class="text-muted small mb-0">{{ __('Scope / Tasks') }}: <span class="text-body">{{ data_get($s2, 'scope', '-') }}</span></div>

                    <hr>

                    <h6 class="mb-2">{{ __('Official Standards & Manuals') }}</h6>
                    <div class="text-muted small mb-2">
                        {{ __('Include references?') }}:
                        <span class="text-body fw-semibold">{{ (string) data_get($s3, 'want_official_standards', '0') === '1' ? __('Yes') : __('No') }}</span>
                    </div>
                    <div class="text-muted small mb-2">
                        {{ __('Jurisdiction') }}: <span class="text-body">{{ data_get($s3, 'jurisdiction', 'AU') }}</span>
                    </div>
                    @php $domains = (array) data_get($s3, 'domains', []); @endphp
                    <div class="text-muted small mb-3">
                        {{ __('Domains') }}:
                        <span class="text-body">{{ empty($domains) ? '-' : implode(', ', $domains) }}</span>
                    </div>

                    @if(!empty($suggestions))
                        <div class="border rounded p-3">
                            <div class="fw-semibold mb-2">{{ __('Titan Zero suggestions') }}</div>
                            <ul class="mb-0">
                                @foreach($suggestions as $s)
                                    @php $ref = is_array($s) ? ($s['reference'] ?? ($s['name'] ?? json_encode($s))) : (string) $s; @endphp
                                    <li>{{ $ref }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="text-muted small">{{ __('No suggestions found (or Titan Zero not available).') }}</div>
                    @endif

                    <hr>

                    <h6 class="mb-2">{{ __('Output preferences') }}</h6>
                    <div class="text-muted small mb-2">{{ __('Tone') }}: <span class="text-body">{{ data_get($s4, 'tone', '-') }}</span></div>
                    <div class="text-muted small mb-0">{{ __('Extra details / notes') }}: <span class="text-body">{{ data_get($s4, 'notes', '-') }}</span></div>
                </div>

                <div class="card-footer d-flex justify-content-between">
                    <a class="btn btn-light" href="{{ route('titan.docs.generator.step', ['session' => $wizard->id, 'step' => 4]) }}">{{ __('Back') }}</a>

                    <form method="POST" action="{{ route('titan.docs.generator.complete', ['session' => $wizard->id]) }}">
                        @csrf
                        <button class="btn btn-success">{{ __('Complete Wizard') }}</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-2">{{ __('What happens next?') }}</h6>
                    <p class="text-muted mb-0">
                        {{ __('After completing the wizard, you will be taken to Titan Docs where you can choose a template and generate the final output using the wizard details.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
