@extends('titan_operator::default.panel.user.client-portal.layout', ['pageTitle' => __('Client Portal Builder')])

@section('client-portal-content')
    @include('titan_operator::default.panel.user.client-portal.partials.template-selector')
    @include('titan_operator::default.panel.user.client-portal.partials.builder-stepper')
    @include('titan_operator::default.panel.user.client-portal.partials.quick-actions')
    @include('titan_operator::default.panel.user.client-portal.build.actions-grid')
    @include('titan_operator::default.panel.user.client-portal.build.titan-operators-list', ['titan_operators' => $titan_operators])
    @include('titan_operator::default.panel.user.client-portal.build.edit-window', ['avatars' => $avatars])
@endsection
