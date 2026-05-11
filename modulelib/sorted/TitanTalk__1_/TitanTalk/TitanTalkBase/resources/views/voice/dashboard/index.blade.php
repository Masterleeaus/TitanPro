@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <h3 class="mb-2">Titan Hello</h3>
            <p class="text-muted">Phone-only calling module: webhooks + outbound dialer + call inbox.</p>

            <div class="d-flex gap-2 flex-wrap">
                <a class="btn btn-primary" href="{{ route('dashboard.user.marketing-bot.voice.calls.index') }}">Open Call Inbox</a>
                <a class="btn btn-outline-primary" href="{{ route('dashboard.user.marketing-bot.voice.calls.dialer') }}">Dialer</a>
                <a class="btn btn-outline-secondary" href="{{ route('dashboard.user.marketing-bot.voice.settings.index') }}">Settings</a>
                <a class="btn btn-outline-secondary" href="{{ route('dashboard.user.marketing-bot.voice.health') }}">Health</a>
            </div>
        </div>
    </div>
</div>
@endsection
