namespace App\Extensions\TitanCommand\System\JobManager\Resources\views\emails;


@php($brand = \App\Extensions\TitanCommand\System\JobManager\Services\BrandingService::get())
@extends('jobmanager::emails.layout')
@section('content')
<p>{{ __('jobmanager::mail.arrival.body', ['when'=>$wo->scheduled_at]) }}</p>
<p><a href="{{ $portalUrl }}">{{ __('jobmanager::mail.generic.portal_cta') }}</a></p>
@endsection
