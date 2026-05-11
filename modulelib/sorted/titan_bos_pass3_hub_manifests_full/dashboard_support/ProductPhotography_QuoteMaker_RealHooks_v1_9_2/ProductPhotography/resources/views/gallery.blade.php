@extends('panel.layout.app', ['disable_tblr' => true])

@section('title', $surfaceName . ' Drafts')
@section('titlebar_title', $surfaceName . ' Drafts')
@section('titlebar_subtitle', __('Saved quote visual drafts and prepared payloads.'))

@section('content')
<div class="py-10">
    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <div class="flex flex-wrap gap-3">
            <button type="button" class="btn btn-primary" onclick="window.location.href='{{ route('dashboard.user.quotemaker.index') }}'">
                {{ __('Create Quote Visual') }}
            </button>
            <button type="button" class="btn btn-outline-primary" onclick="window.location.href='{{ route('dashboard.user.quotemaker.templates') }}'">
                {{ __('Browse Templates') }}
            </button>
        </div>
    </div>

    <div class="row g-4">
        @forelse($items as $item)
            <div class="col-12 col-md-6 col-xl-4">
                @include('productphotography::partials.result_card', ['item' => $item])
            </div>
        @empty
            <div class="col-12">
                <x-card variant="outline" size="md">
                    <p class="text-2xs text-foreground/60">{{ __('No saved drafts yet.') }}</p>
                </x-card>
            </div>
        @endforelse
    </div>
</div>
@endsection
