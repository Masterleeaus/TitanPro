@extends('panel.layout.app', ['disable_tblr' => true])

@section('title', $surfaceName . ' Templates')
@section('titlebar_title', $surfaceName . ' Templates')
@section('titlebar_subtitle', __('Reusable service-quote templates for faster visual preparation.'))

@section('content')
<div class="py-10">
    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <div class="flex flex-wrap gap-3">
            <button type="button" class="btn btn-primary" onclick="window.location.href='{{ route('dashboard.user.quotemaker.builder') }}'">
                {{ __('Create Template with Wizard') }}
            </button>
            <button type="button" class="btn btn-outline-primary" onclick="window.location.href='{{ route('dashboard.user.quotemaker.index') }}'">
                {{ __('Create Quote Visual') }}
            </button>
            <button type="button" class="btn btn-outline-primary" onclick="window.location.href='{{ route('dashboard.user.quotemaker.gallery') }}'">
                {{ __('Open Drafts') }}
            </button>
        </div>
    </div>

    <div class="row g-4">
        @foreach($templates as $template)
            <div class="col-12 col-md-6 col-xl-4">
                @include('productphotography::partials.template_card', ['template' => $template])
            </div>
        @endforeach
    </div>
</div>
@endsection
