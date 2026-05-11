@extends('titan_operator::default.panel.user.client-portal.layout', ['pageTitle' => __('Client Portal Runtime')])

@section('client-portal-content')
    @include('titan_operator::default.panel.user.client-portal.partials.runtime-panel')
    @include('titan_operator::default.panel.user.client-portal.partials.runtime-status')
    @include('titan_operator::default.panel.user.client-portal.partials.runtime-checklist')
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        @include('titan_operator::default.panel.user.client-portal.partials.channel-overview')
        @include('titan_operator::default.panel.user.client-portal.partials.workflow-overview')
    </div>
@endsection
