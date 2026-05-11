@extends('titan_operator::default.panel.user.client-portal.layout', ['pageTitle' => __('Client Portal Templates')])

@section('client-portal-content')
    @include('titan_operator::default.panel.user.client-portal.partials.template-selector')
    @include('titan_operator::default.panel.user.client-portal.partials.template-grid')
    @include('titan_operator::default.panel.user.client-portal.partials.builder-stepper')
@endsection
