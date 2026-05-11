@extends('titan_operator::default.panel.user.client-portal.layout', ['pageTitle' => __('Install Client Portal App')])

@section('client-portal-content')
    @include('titan_operator::default.panel.user.client-portal.partials.install-actions')
    @include('titan_operator::default.panel.user.client-portal.partials.install-checklist')
    @include('titan_operator::default.panel.user.client-portal.partials.runtime-status')
    @include('titan_operator::default.panel.user.client-portal.partials.runtime-checklist')
    @include('titan_operator::default.panel.user.client-portal.partials.runtime-panel')
@endsection
