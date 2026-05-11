@extends('layouts.app')

@section('page-title')
    {{ __('Titan Docs Generator') }}
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="mb-1">{{ __('Titan Docs Generator') }}</h4>
                        <p class="text-muted mb-0">{{ __('Step 3 of 4') }} — {{ __('Official Standards & Manuals') }}</p>
                    </div>
                    <div class="text-muted small">{{ __('This step is asked every time') }}</div>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('titan.docs.generator.save', ['session' => $wizard->id, 'step' => 3]) }}">
        @csrf
        <div class="card">
            <div class="card-body">

                <div class="alert alert-info mb-4">
                    <strong>{{ __('Official Standards & Manuals') }}</strong>
                    <div class="text-muted">
                        {{ __('If you choose Yes, Titan Zero will suggest relevant official standards/manual references (if available).') }}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">{{ __('Include official standards references?') }}</label>
                        @php $want = (string) data_get($data, 'step_3.want_official_standards', '0'); @endphp
                        <select name="want_official_standards" class="form-control">
                            <option value="0" {{ $want === '0' ? 'selected' : '' }}>{{ __('No') }}</option>
                            <option value="1" {{ $want === '1' ? 'selected' : '' }}>{{ __('Yes') }}</option>
                        </select>
                        <small class="text-muted">{{ __('You will always be asked this question for every doc.') }}</small>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">{{ __('Jurisdiction') }}</label>
                        <input type="text" name="jurisdiction" class="form-control"
                               value="{{ data_get($data, 'step_3.jurisdiction', 'AU') }}" placeholder="AU">
                        <small class="text-muted">{{ __('Examples: AU, NSW, VIC') }}</small>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">{{ __('Domains') }}</label>
                        @php $domainsSelected = (array) data_get($data, 'step_3.domains', []); @endphp
                        <select name="domains[]" class="form-control" multiple>
                            @foreach (['Safety','Electrical','Plumbing','Structural','Environmental','General Construction'] as $d)
                                <option value="{{ $d }}" {{ in_array($d, $domainsSelected) ? 'selected' : '' }}>{{ __($d) }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">{{ __('Hold Ctrl/Cmd to select multiple.') }}</small>
                    </div>
                </div>

                @php $suggestions = (array) data_get($data, 'step_3.standards_suggestions', []); @endphp
                @if (!empty($suggestions))
                    <hr>
                    <h5 class="mb-2">{{ __('Titan Zero suggestions') }}</h5>
                    <p class="text-muted mb-3">{{ __('These will be included as reference context when generating the document.') }}</p>

                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>{{ __('Reference') }}</th>
                                    <th>{{ __('Notes') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($suggestions as $s)
                                    @php
                                        $ref = is_array($s) ? ($s['reference'] ?? ($s['name'] ?? json_encode($s))) : (string) $s;
                                        $notes = is_array($s) ? ($s['notes'] ?? '') : '';
                                    @endphp
                                    <tr>
                                        <td class="fw-semibold">{{ $ref }}</td>
                                        <td class="text-muted">{{ $notes }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-muted small">
                        {{ __('Save this step to fetch suggestions from Titan Zero (if available).') }}
                    </div>
                @endif

            </div>

            <div class="card-footer d-flex justify-content-between">
                <a class="btn btn-light" href="{{ route('titan.docs.generator.step', ['session' => $wizard->id, 'step' => 2]) }}">{{ __('Back') }}</a>
                <button type="submit" class="btn btn-primary">{{ __('Save & Continue') }}</button>
            </div>
        </div>
    </form>
</div>
@endsection
