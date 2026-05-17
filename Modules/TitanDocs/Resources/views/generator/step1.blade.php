@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="mb-1">{{ __('Titan Docs Generator') }}</h4>
                        <p class="text-muted mb-0">{{ __('Step 1 of 4') }}</p>
                    </div>
                    <div class="text-muted small">{{ __('Standards step is asked every time') }}</div>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('titan.docs.generator.save', ['session' => $wizard->id, 'step' => 1]) }}">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('Document Kind') }}</label>
                        <select name="doc_kind" class="form-control">
                            <option value="doc" {{ ($wizard->doc_kind === 'doc') ? 'selected' : '' }}>{{ __('Document') }}</option>
                            <option value="swms" {{ ($wizard->doc_kind === 'swms') ? 'selected' : '' }}>{{ __('SWMS') }}</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('Document Type') }}</label>
                        <input type="text" name="doc_type" class="form-control" value="{{ data_get($data, 'step_1.doc_type') }}" placeholder="{{ __('e.g. Working at Heights SWMS, Client Letter, Toolbox Talk') }}">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('Title') }}</label>
                    <input type="text" name="title" class="form-control" value="{{ data_get($data, 'step_1.title') }}" placeholder="{{ __('Short descriptive title') }}">
                </div>

            </div>
            <div class="card-footer d-flex justify-content-between">
                <a class="btn btn-light" href="{{ $step > 1 ? route('titan.docs.generator.step', ['session' => $wizard->id, 'step' => $step-1]) : route('titan.docs.index') }}">{{ __('Back') }}</a>
                <button type="submit" class="btn btn-primary">{{ __('Save & Continue') }}</button>
            </div>
        </div>
    </form>
</div>
@endsection
