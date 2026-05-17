@extends('titan_operator::default.panel.user.client-portal.layout', ['pageTitle' => __('Embed Client Portal')])
@section('client-portal-content')
    @include('titan_operator::default.panel.user.client-portal.partials.template-selector')
    @include('titan_operator::default.panel.user.client-portal.partials.runtime-panel')
    @include('titan_operator::dashboard.embed')
@endsection
