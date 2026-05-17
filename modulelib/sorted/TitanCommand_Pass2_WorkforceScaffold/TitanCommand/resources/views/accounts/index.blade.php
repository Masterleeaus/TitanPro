@php
    $theme = get_theme();
@endphp

@extends('panel.layout.app', ['disable_tblr' => true])
@section('title', __('Channels Accounts'))
@section('titlebar_subtitle', __('You can connect and manage multiple channels accounts from here.'))
@section('titlebar_actions')
    @include('command-agent::components.titlebar-actions')
@endsection

@section('content')
    <div @class(['px-5', 'py-5' => $theme !== 'command-agent-dashboard'])>
        @include('command-agent::accounts.banner')
        @include('command-agent::accounts.container-cards', ['containers' => $containers])
        @include('command-agent::accounts.containers-table', ['containers' => $userPlatforms])
    </div>
@endsection
