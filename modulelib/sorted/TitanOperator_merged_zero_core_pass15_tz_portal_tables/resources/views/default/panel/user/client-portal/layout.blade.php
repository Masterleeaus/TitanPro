@extends('panel.layout.app', ['disable_tblr' => true])

@section('title', $pageTitle ?? __('Client Portal'))

@section('content')
    <div class="py-10 space-y-6">
        @include('titan_operator::default.panel.user.client-portal.partials.hero')
        @include('titan_operator::default.panel.user.client-portal.partials.nav')
        @include('titan_operator::default.panel.user.client-portal.partials.pwa-banner')
        @yield('client-portal-content')
    </div>
@endsection
