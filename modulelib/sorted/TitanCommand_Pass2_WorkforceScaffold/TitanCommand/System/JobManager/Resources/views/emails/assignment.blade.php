namespace App\Extensions\TitanCommand\System\JobManager\Resources\views\emails;


@php($brand = \App\Extensions\TitanCommand\System\JobManager\Services\BrandingService::get())
@extends('jobmanager::emails.layout')
@section('content')
<p>{{ __('jobmanager::mail.assignment.greeting') }}</p>
<p>{{ __('jobmanager::mail.assignment.body', ['id'=>$wo->id, 'when'=>$wo->scheduled_at]) }}</p>
<p><a href="{{ $portalUrl }}">{{ __('jobmanager::mail.assignment.cta') }}</a></p>
@endsection
