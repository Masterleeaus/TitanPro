@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="mb-1">{{ __('Titan Docs Generator') }}</h4>
                        <p class="text-muted mb-0">{{ __('Step 3 of 4') }}</p>
                    </div>
                    <div class="text-muted small">{{ __('Standards step is asked every time') }}</div>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('titan.docs.generator.save', ['session' => $wizard->id, 'step' => 3]) }}">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="alert alert-info">
                    <strong>{{ __('Official Standards & Manuals') }}</strong>
                    <div class="text-muted">{{ __('Titan Zero is trained on official national standards. Choose whether to include official references for this document.') }}</div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">{{ __('Include official standards references?') }}</label>
                        <select name="want_official_standards" class="form-control">
                            <option value="0" {{ data_get($data, 'step_3.want_official_standards') == '0' ? 'selected' : '' }}>{{ __('No') }}</option>
                            <option value="1" {{ data_get($data, 'step_3.want_official_standards') == '1' ? 'selected' : '' }}>{{ __('Yes') }}</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">{{ __('Jurisdiction') }}</label>
                        <input type="text" name="jurisdiction" class="form-control" value="{{ data_get($data, 'step_3.jurisdiction', 'AU') }}" placeholder="AU">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">{{ __('Domains') }}</label>
                        <select name="domains[]" class="form-control" multiple>
                            @php $domains = (array) data_get($data, 'step_3.domains', []); @endphp
                            @foreach(['Safety','Electrical','Plumbing','Structural','Environmental','Fire','Asbestos','Working at Heights'] as $d)
                                <option value="{{ $d }}" {{ in_array($d, $domains) ? 'selected' : '' }}>{{ $d }}</option>
                            @endforeach
                        </select>
                        <div class="text-muted small mt-1">{{ __('Hold Cmd/Ctrl to select multiple') }}</div>
                    </div>
                </div>

                @if(!empty($data['standards_suggestions'] ?? []))
                    <div class="mt-3">
                        <h5 class="mb-2">{{ __('Suggested references (from Titan Zero)') }}</h5>
                        <ul class="mb-0">
                            @foreach(($data['standards_suggestions'] ?? []) as $ref)
                                <li>
                                    <strong>{{ data_get($ref, 'code', data_get($ref, 'title', 'Standard')) }}</strong>
                                    @if(data_get($ref, 'summary'))
                                        <span class="text-muted">— {{ data_get($ref, 'summary') }}</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <div class="text-muted small">{{ __('Save this step to fetch suggestions from Titan Zero (if available).') }}</div>
                @endif

            </div>
            <div class="card-footer d-flex justify-content-between">
                <a class="btn btn-light" href="{{ $step > 1 ? route('titan.docs.generator.step', ['session' => $wizard->id, 'step' => $step-1]) : route('titan.docs.index') }}">{{ __('Back') }}</a>
                <button type="submit" class="btn btn-primary">{{ __('Save & Continue') }}</button>
            </div>
        </div>
    </form>
</div>
@endsection
