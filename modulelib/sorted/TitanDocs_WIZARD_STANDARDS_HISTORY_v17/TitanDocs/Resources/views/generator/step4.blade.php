@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="mb-1">{{ __('Titan Docs Generator') }}</h4>
                        <p class="text-muted mb-0">{{ __('Step 4 of 4') }}</p>
                    </div>
                    <div class="text-muted small">{{ __('Standards step is asked every time') }}</div>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('titan.docs.generator.save', ['session' => $wizard->id, 'step' => 4]) }}">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">{{ __('Extra Details / Notes') }}</label>
                    <textarea name="notes" class="form-control" rows="5" placeholder="{{ __('Any extra info to ensure the document is correct') }}">{{ data_get($data, 'step_4.notes') }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('Tone / Style') }}</label>
                    <select name="tone" class="form-control">
                        @php $tone = data_get($data, 'step_4.tone', 'Professional'); @endphp
                        @foreach(['Professional','Plain English','Regulatory','Client-friendly'] as $t)
                            <option value="{{ $t }}" {{ $tone === $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
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
