
namespace Modules\JobManager\Resources\views\emails;


@php($brand = \Modules\JobManager\Services\BrandingService::get())
@extends('jobmanager::emails.layout')
@section('content')
<p>{{ __('jobmanager::mail.completion.body', ['id'=>$wo->id]) }}</p>
<p><a href="{{ $pdfUrl }}">{{ __('jobmanager::mail.completion.cta_pdf') }}</a></p>
@endsection