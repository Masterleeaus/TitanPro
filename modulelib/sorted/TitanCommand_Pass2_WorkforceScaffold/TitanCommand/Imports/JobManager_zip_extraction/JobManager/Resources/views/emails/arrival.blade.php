
namespace Modules\JobManager\Resources\views\emails;


@php($brand = \Modules\JobManager\Services\BrandingService::get())
@extends('jobmanager::emails.layout')
@section('content')
<p>{{ __('jobmanager::mail.arrival.body', ['when'=>$wo->scheduled_at]) }}</p>
<p><a href="{{ $portalUrl }}">{{ __('jobmanager::mail.generic.portal_cta') }}</a></p>
@endsection