@extends('titan_operator::default.panel.user.client-portal.layout', ['pageTitle' => __('Client Portal Preview')])

@section('client-portal-content')
    @include('titan_operator::default.panel.user.client-portal.partials.template-selector')
    @include('titan_operator::default.panel.user.client-portal.partials.runtime-panel')
    @include('titan_operator::default.panel.user.client-portal.partials.runtime-checklist')
    @include('titan_operator::default.panel.user.client-portal.runtime.frontend-ui-preview')
@endsection
