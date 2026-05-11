@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="mb-1">{{ __('Titan Docs Generator') }}</h4>
                        <p class="text-muted mb-0">{{ __('Step 2 of 4') }}</p>
                    </div>
                    <div class="text-muted small">{{ __('Standards step is asked every time') }}</div>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('titan.docs.generator.save', ['session' => $wizard->id, 'step' => 2]) }}">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('Trade / Discipline') }}</label>
                        <input type="text" name="trade" class="form-control" value="{{ data_get($data, 'step_2.trade') }}" placeholder="{{ __('e.g. Plumbing, Electrical, Carpentry') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('Site / Project Context') }}</label>
                        <input type="text" name="site_context" class="form-control" value="{{ data_get($data, 'step_2.site_context') }}" placeholder="{{ __('e.g. Residential renovation, commercial fit-out') }}">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('Scope / Tasks') }}</label>
                    <textarea name="scope" class="form-control" rows="4" placeholder="{{ __('List the work scope/tasks in plain language') }}">{{ data_get($data, 'step_2.scope') }}</textarea>
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
