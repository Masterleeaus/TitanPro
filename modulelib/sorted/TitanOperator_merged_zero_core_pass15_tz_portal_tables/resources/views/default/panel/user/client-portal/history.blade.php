@extends('titan_operator::default.panel.user.client-portal.layout', ['pageTitle' => __('Client Portal History')])

@section('client-portal-content')
    @include('titan_operator::default.panel.user.client-portal.partials.stats-grid')
    @include('titan_operator::default.panel.user.client-portal.partials.template-selector')
    @include('titan_operator::default.panel.user.client-portal.build.history.chats-history')
@endsection
