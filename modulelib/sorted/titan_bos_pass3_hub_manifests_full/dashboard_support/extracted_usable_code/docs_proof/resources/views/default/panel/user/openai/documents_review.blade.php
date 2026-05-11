
@extends('panel.layout.app', ['disable_tblr' => true])
@section('title', __('Documents Review Board'))
@section('titlebar_title', __('Documents Review Board'))
@section('titlebar_actions')
    <div class="flex flex-wrap items-center gap-2">
        <x-button variant="ghost-shadow" href="{{ route('dashboard.user.openai.documents.all') }}">{{ __('All Documents') }}</x-button>
        <x-button variant="ghost-shadow" href="{{ route('dashboard.user.openai.documents.review', ['lane' => 'review']) }}">{{ __('Needs Review') }}</x-button>
        <x-button variant="ghost-shadow" href="{{ route('dashboard.user.openai.documents.review', ['lane' => 'legal']) }}">{{ __('Legal') }}</x-button>
        <x-button variant="ghost-shadow" href="{{ route('dashboard.user.openai.documents.review', ['lane' => 'proof']) }}">{{ __('Proof') }}</x-button>
        <x-button variant="ghost-shadow" href="{{ route('dashboard.user.openai.documents.packets') }}">{{ __('Packet Planner') }}</x-button>
    </div>
@endsection

@section('content')
    <div class="py-10 space-y-6">
        <div class="grid gap-4 md:grid-cols-4">
            <x-card class="p-5"><p class="text-2xs uppercase tracking-wide text-foreground/60">{{ __('Ready') }}</p><p class="mt-2 text-3xl font-semibold text-heading-foreground">{{ $proofBoard['stats']['ready'] ?? 0 }}</p></x-card>
            <x-card class="p-5"><p class="text-2xs uppercase tracking-wide text-foreground/60">{{ __('Needs Review') }}</p><p class="mt-2 text-3xl font-semibold text-heading-foreground">{{ $proofBoard['stats']['review'] ?? 0 }}</p></x-card>
            <x-card class="p-5"><p class="text-2xs uppercase tracking-wide text-foreground/60">{{ __('Legal') }}</p><p class="mt-2 text-3xl font-semibold text-heading-foreground">{{ $proofBoard['stats']['legal'] ?? 0 }}</p></x-card>
            <x-card class="p-5"><p class="text-2xs uppercase tracking-wide text-foreground/60">{{ __('Proof Assets') }}</p><p class="mt-2 text-3xl font-semibold text-heading-foreground">{{ $proofSummary['proof'] ?? 0 }}</p></x-card>
        </div>

        <div class="grid gap-4 xl:grid-cols-[minmax(0,1.35fr)_minmax(0,1fr)]">
            <x-card class="p-5">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-2xs uppercase tracking-wide text-foreground/60">{{ __('Lane Focus') }}</p>
                        <h3 class="mt-1 text-lg font-semibold text-heading-foreground">{{ $selectedLane === 'all' ? __('Everything in motion') : ucfirst($selectedLane) }}</h3>
                        <p class="mt-1 text-xs text-foreground/60">{{ __('Triage documents without leaving the original workbook system.') }}</p>
                    </div>
                    <span class="rounded-full bg-foreground/5 px-3 py-1 text-2xs">{{ count($laneItems ?? []) }} {{ __('items') }}</span>
                </div>

                <div class="mt-5 grid gap-3 md:grid-cols-5">
                    @foreach (['all' => __('All'), 'review' => __('Review'), 'legal' => __('Legal'), 'proof' => __('Proof'), 'ready' => __('Ready')] as $laneKey => $laneLabel)
                        <a class="rounded-2xl border border-black/5 p-3 text-center transition hover:bg-foreground/5 {{ $selectedLane === $laneKey ? 'bg-foreground/5' : '' }}" href="{{ route('dashboard.user.openai.documents.review', ['lane' => $laneKey]) }}">
                            <span class="block text-2xs uppercase tracking-wide text-foreground/60">{{ $laneLabel }}</span>
                        </a>
                    @endforeach
                </div>

                <div class="mt-5 space-y-3">
                    @forelse(($laneItems ?? collect()) as $entry)
                        <a class="block rounded-2xl border border-black/5 p-4 transition hover:bg-foreground/5" href="{{ route('dashboard.user.openai.documents.single', $entry->slug) }}">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-sm font-semibold text-heading-foreground">{{ $entry->title ?: str($entry->input)->limit(64) }}</p>
                                    <p class="mt-1 text-2xs text-foreground/60">{{ $entry->proof_meta['summary'] ?? __('Core document') }}</p>
                                </div>
                                <span class="rounded-full bg-foreground/5 px-2.5 py-1 text-2xs">{{ $entry->proof_meta['lane_label'] ?? __('Open') }}</span>
                            </div>
                            <div class="mt-3 flex flex-wrap gap-2">
                                @foreach(($entry->proof_meta['tags'] ?? []) as $tag)
                                    <span class="rounded-full bg-foreground/5 px-2.5 py-1 text-2xs">{{ $tag }}</span>
                                @endforeach
                                <span class="rounded-full bg-accent/10 px-2.5 py-1 text-2xs text-accent">{{ $entry->proof_meta['readiness_score'] ?? 0 }}%</span>
                            </div>
                        </a>
                    @empty
                        <p class="text-xs text-foreground/60">{{ __('Nothing in this lane right now.') }}</p>
                    @endforelse
                </div>
            </x-card>

            <div class="space-y-4">
                <x-card class="p-5">
                    <p class="text-2xs uppercase tracking-wide text-foreground/60">{{ __('Spotlight') }}</p>
                    @if(($proofBoard['spotlight'] ?? null))
                        <div class="mt-3 rounded-2xl border border-black/5 p-4">
                            <p class="text-sm font-semibold text-heading-foreground">{{ $proofBoard['spotlight']->title ?: str($proofBoard['spotlight']->input)->limit(56) }}</p>
                            <p class="mt-1 text-2xs text-foreground/60">{{ $proofBoard['spotlight']->proof_meta['action_label'] ?? __('Review') }}</p>
                            <div class="mt-3 flex flex-wrap gap-2">
                                <x-button variant="ghost-shadow" href="{{ route('dashboard.user.openai.documents.single', $proofBoard['spotlight']->slug) }}">{{ __('Open') }}</x-button>
                                <x-button variant="ghost-shadow" href="{{ route('dashboard.user.openai.documents.proof.export', $proofBoard['spotlight']->slug) }}">{{ __('Export Proof') }}</x-button>
                            </div>
                        </div>
                    @else
                        <p class="mt-3 text-xs text-foreground/60">{{ __('No spotlight item yet.') }}</p>
                    @endif
                </x-card>

                <x-card class="p-5">
                    <p class="text-2xs uppercase tracking-wide text-foreground/60">{{ __('Packet Streams') }}</p>
                    <div class="mt-3 space-y-3">
                        @foreach(['customer_share' => __('Customer Share'), 'legal_packet' => __('Legal Packet'), 'review_queue' => __('Review Queue')] as $packetKey => $packetLabel)
                            <div class="rounded-2xl border border-black/5 p-4">
                                <div class="flex items-center justify-between gap-3">
                                    <strong class="text-sm text-heading-foreground">{{ $packetLabel }}</strong>
                                    <span class="text-2xs text-foreground/60">{{ count($packetPlanner[$packetKey] ?? []) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-card>
            </div>
        </div>
    </div>
@endsection
