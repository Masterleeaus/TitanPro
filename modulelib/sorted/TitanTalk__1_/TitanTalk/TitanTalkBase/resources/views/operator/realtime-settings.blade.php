@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">TitanTalk Realtime</h3>
        <span class="text-muted">Operator inbox live updates and notification transport</span>
    </div>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('dashboard.user.titan-talk.operator.realtime.update') }}">
                @csrf
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="titantalk_realtime_enabled" value="1" id="tt-enabled" @checked($config['enabled'])>
                    <label class="form-check-label" for="tt-enabled">Enable realtime transport</label>
                </div>
                <div class="mb-3">
                    <label class="form-label">Driver</label>
                    <input class="form-control" name="titantalk_realtime_driver" value="{{ $config['driver'] }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Ably Public Key</label>
                    <input class="form-control" name="titantalk_ably_public_key" value="{{ $config['ably_public_key'] }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Ably Private Key</label>
                    <input class="form-control" name="titantalk_ably_private_key" value="{{ $config['ably_private_key'] }}">
                </div>
                <button class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
</div>
@endsection
