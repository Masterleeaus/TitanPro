@extends('titantalk::layouts.app')

@section('titantalk-content')
<div class="card">
    <div class="card-body">
        <h4 class="mb-3">TitanTalk Settings</h4>
        <form method="POST" action="{{ route('titantalk.settings.update') }}">
            @csrf
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" name="enabled" value="1" {{ old('enabled', true) ? 'checked' : '' }}>
                <label class="form-check-label">Enable TitanTalk</label>
            </div>
            <div class="mb-3">
                <label class="form-label">Default language</label>
                <input class="form-control" name="default_language" value="{{ old('default_language','en') }}">
            </div>
            <button class="btn btn-primary">Save</button>
        </form>
    </div>
</div>
@endsection
