@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card mb-3">
        <div class="card-body">
            <h4 class="mb-1">{{ __('Review & Generate') }}</h4>
            <p class="text-muted mb-0">{{ __('Confirm details, including any official standards references.') }}</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-2">{{ __('Summary') }}</h5>
                    <pre class="mb-0" style="white-space: pre-wrap;">{{ json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a class="btn btn-light" href="{{ route('titan.docs.generator.step', ['session' => $wizard->id, 'step' => 4]) }}">{{ __('Back') }}</a>
                    <form method="POST" action="{{ route('titan.docs.generator.complete', ['session' => $wizard->id]) }}">
                        @csrf
                        <button class="btn btn-success">{{ __('Proceed to Generator') }}</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-2">{{ __('Official References') }}</h6>
                    @php $refs = $data['standards_suggestions'] ?? []; @endphp
                    @if(!empty($refs))
                        <ul class="mb-0">
                            @foreach($refs as $ref)
                                <li>{{ data_get($ref, 'code', data_get($ref, 'title', 'Standard')) }}</li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-muted small">{{ __('None selected or none returned.') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
