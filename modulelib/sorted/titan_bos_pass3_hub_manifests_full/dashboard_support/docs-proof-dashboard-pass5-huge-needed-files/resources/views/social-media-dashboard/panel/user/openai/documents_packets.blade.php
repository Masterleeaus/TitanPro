
@extends('panel.layout.app', ['disable_tblr' => true])
@section('title', __('Packet Planner'))
@section('titlebar_title', __('Packet Planner'))
@section('titlebar_actions')
    <div class="flex flex-wrap items-center gap-2">
        <x-button variant="ghost-shadow" href="{{ route('dashboard.user.openai.documents.all') }}">{{ __('All Documents') }}</x-button>
        <x-button variant="ghost-shadow" href="{{ route('dashboard.user.openai.documents.review') }}">{{ __('Review Board') }}</x-button>
    </div>
@endsection

@section('content')
    <div class="py-10 space-y-6">
        <div class="grid gap-4 md:grid-cols-4">
            <x-card class="p-5"><p class="text-2xs uppercase tracking-wide text-foreground/60">{{ __('Ready') }}</p><p class="mt-2 text-3xl font-semibold text-heading-foreground">{{ $proofBoard['stats']['ready'] ?? 0 }}</p></x-card>
            <x-card class="p-5"><p class="text-2xs uppercase tracking-wide text-foreground/60">{{ __('Review') }}</p><p class="mt-2 text-3xl font-semibold text-heading-foreground">{{ $proofBoard['stats']['review'] ?? 0 }}</p></x-card>
            <x-card class="p-5"><p class="text-2xs uppercase tracking-wide text-foreground/60">{{ __('Legal') }}</p><p class="mt-2 text-3xl font-semibold text-heading-foreground">{{ $proofBoard['stats']['legal'] ?? 0 }}</p></x-card>
            <x-card class="p-5"><p class="text-2xs uppercase tracking-wide text-foreground/60">{{ __('Proof') }}</p><p class="mt-2 text-3xl font-semibold text-heading-foreground">{{ $proofBoard['stats']['proof'] ?? 0 }}</p></x-card>
        </div>

        <div class="grid gap-4 xl:grid-cols-2">
            @foreach(['customer_share' => __('Customer Share Pack'), 'legal_packet' => __('Legal Packet'), 'review_queue' => __('Review Queue'), 'ops_archive' => __('Ops Archive')] as $packetKey => $packetLabel)
                <x-card class="p-5">
                    <div class="mb-4 flex items-center justify-between gap-2">
                        <div>
                            <p class="text-2xs uppercase tracking-wide text-foreground/60">{{ $packetLabel }}</p>
                            <h3 class="text-lg font-semibold text-heading-foreground">{{ __('Assemble from the original documents system') }}</h3>
                        </div>
                    </div>
                    <div class="space-y-3">
                        @forelse(($packetPlanner[$packetKey] ?? collect()) as $entry)
                            <a class="block rounded-2xl border border-black/5 p-4 transition hover:bg-foreground/5" href="{{ route('dashboard.user.openai.documents.single', $entry->slug) }}">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-semibold text-heading-foreground">{{ $entry->title ?: str($entry->input)->limit(60) }}</p>
                                        <p class="mt-1 text-2xs text-foreground/60">{{ $entry->proof_meta['summary'] ?? __('Core document') }}</p>
                                    </div>
                                    <span class="rounded-full bg-foreground/5 px-2.5 py-1 text-2xs">{{ $entry->proof_meta['lane_label'] ?? __('Open') }}</span>
                                </div>
                            </a>
                        @empty
                            <p class="text-xs text-foreground/60">{{ __('No documents queued in this packet yet.') }}</p>
                        @endforelse
                    </div>
                </x-card>
            @endforeach
        </div>
    </div>
@endsection
