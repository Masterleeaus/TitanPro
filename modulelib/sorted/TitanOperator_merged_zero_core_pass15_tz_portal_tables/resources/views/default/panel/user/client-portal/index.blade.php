@extends('titan_operator::default.panel.user.client-portal.layout', ['pageTitle' => __('Client Portal Dashboard')])

@section('client-portal-content')
    @include('titan_operator::default.panel.user.client-portal.partials.template-selector')
    @include('titan_operator::default.panel.user.client-portal.partials.stats-grid')
    @include('titan_operator::default.panel.user.client-portal.partials.quick-actions')
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
        <div class="space-y-6 xl:col-span-2">
            @include('titan_operator::default.panel.user.client-portal.partials.recent-conversations')
            @include('titan_operator::default.panel.user.client-portal.partials.runtime-panel')
        </div>
        <div class="space-y-6">
            @include('titan_operator::default.panel.user.client-portal.partials.recent-operators')
            @include('titan_operator::default.panel.user.client-portal.partials.channel-overview')
            @include('titan_operator::default.panel.user.client-portal.partials.workflow-overview')
        </div>
    </div>
@endsection
