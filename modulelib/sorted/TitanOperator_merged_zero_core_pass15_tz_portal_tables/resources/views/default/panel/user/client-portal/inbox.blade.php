@extends('titan_operator::default.panel.user.client-portal.layout', ['pageTitle' => __('Client Portal Inbox')])

@section('client-portal-content')
    @include('titan_operator::default.panel.user.client-portal.partials.stats-grid')
    @include('titan_operator::default.panel.user.client-portal.partials.template-selector')
    @include('titan_operator::default.panel.user.client-portal.partials.inbox-list')
@endsection
