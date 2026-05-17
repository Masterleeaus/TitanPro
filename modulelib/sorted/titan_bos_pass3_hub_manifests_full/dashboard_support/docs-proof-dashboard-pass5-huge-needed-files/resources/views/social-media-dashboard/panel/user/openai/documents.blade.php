
@php
    $sort_buttons = [
        ['label' => __('Date'), 'sort' => 'created_at'],
        ['label' => __('Title'), 'sort' => 'title'],
        ['label' => __('Type'), 'sort' => 'openai_id'],
        ['label' => __('Cost'), 'sort' => 'credits'],
    ];

    $filter_buttons = [
        ['label' => __('All'), 'filter' => 'all'],
        ['label' => __('Favorites'), 'filter' => 'favorites'],
        ['label' => __('Text'), 'filter' => 'text'],
        ['label' => __('Image'), 'filter' => 'image'],
        ['label' => __('Video'), 'filter' => 'video'],
        ['label' => __('Code'), 'filter' => 'code'],
        ['label' => __('Business'), 'filter' => 'business'],
        ['label' => __('Legal'), 'filter' => 'legal'],
        ['label' => __('Proof'), 'filter' => 'proof'],
        ['label' => __('Needs Review'), 'filter' => 'needs_review'],
    ];
@endphp

@extends('panel.layout.app', ['disable_tblr' => true])
@section('title', __('My Documents'))
@section('titlebar_title')
    {{ $currfolder?->name ? __("Folder: $currfolder?->name") : __('My Documents') }}
@endsection

@if ($items && count($items) > 0)
    @section('titlebar_after')
        <div class="flex flex-wrap items-center justify-between gap-2 lg:flex-nowrap">
            @if (blank($currfolder))
                <div class="flex flex-wrap items-center gap-1">
                    <x-dropdown.dropdown class="pe-3" offsetY="1rem">
                        <x-slot:trigger class="whitespace-nowrap py-1.5" variant="link" size="xs">
                            {{ __('Sort by:') }}
                            <x-tabler-arrows-sort class="size-4" />
                        </x-slot:trigger>
                        <x-slot:dropdown class="overflow-hidden text-2xs font-medium">
                            <form class="lqd-sort-list flex flex-col" action="{{ route('dashboard.user.openai.documents.all', ['id' => $currfolder?->id, 'listOnly' => 'true']) }}" method="GET" x-init x-target="lqd-docs-container" @submit="$store.documentsFilter.changePage('1')">
                                <input type="hidden" name="filter" :value="$store.documentsFilter.filter">
                                <input type="hidden" name="page" value="1">
                                <input type="hidden" name="sortAscDesc" :value="$store.documentsFilter.sortAscDesc">
                                @foreach ($sort_buttons as $button)
                                    <button class="group flex w-full items-center gap-1 px-3 py-2 hover:bg-foreground/5 [&.active]:bg-foreground/5" :class="$store.documentsFilter.sort === '{{ $button['sort'] }}' && 'active'" name="sort" value="{{ $button['sort'] }}" @click="$store.documentsFilter.changeSort('{{ $button['sort'] }}')">
                                        {{ $button['label'] }}
                                        <x-tabler-caret-down-filled class="size-3 opacity-0 transition-all group-[&.active]:opacity-80" ::class="$store.documentsFilter.sortAscDesc === 'asc' && 'rotate-180'" />
                                    </button>
                                @endforeach
                            </form>
                        </x-slot:dropdown>
                    </x-dropdown.dropdown>

                    <form class="lqd-filter-list flex flex-wrap items-center gap-x-4 gap-y-2 text-heading-foreground max-sm:gap-3" action="{{ route('dashboard.user.openai.documents.all', ['id' => $currfolder?->id, 'listOnly' => 'true']) }}" method="GET" x-init x-target="lqd-docs-container" @submit="$store.documentsFilter.changePage('1')">
                        <input type="hidden" name="sort" :value="$store.documentsFilter.sort">
                        <input type="hidden" name="page" value="1">
                        <input type="hidden" name="sortAscDesc" :value="$store.documentsFilter.sortAscDesc">
                        @foreach ($filter_buttons as $button)
                            <x-button class="lqd-filter-btn inline-flex px-2.5 py-0.5 transition-colors hover:bg-foreground/5 [&.active]:bg-foreground/5 hover:translate-y-0 text-2xs leading-tight" tag="button" type="submit" name="filter" value="{{ $button['filter'] }}" variant="ghost" ::class="$store.documentsFilter.filter === '{{ $button['filter'] }}' && 'active'" @click="$store.documentsFilter.changeFilter('{{ $button['filter'] }}')">
                                {{ $button['label'] }}
                            </x-button>
                        @endforeach
                    </form>
                </div>

                <div class="lqd-posts-view-toggle lqd-docs-view-toggle lqd-view-toggle relative z-1 flex items-center gap-2 lg:ms-auto lg:justify-end">
                    <button class="lqd-view-toggle-trigger inline-flex size-7 items-center justify-center rounded-md transition-colors hover:bg-foreground/5 [&.active]:bg-foreground/5" :class="$store.docsViewMode.docsViewMode === 'list' && 'active'" x-init @click="$store.docsViewMode.change('list')" title="List view"><x-tabler-list class="size-5" stroke-width="1.5" /></button>
                    <button class="lqd-view-toggle-trigger inline-flex size-7 items-center justify-center rounded-md transition-colors hover:bg-foreground/5 [&.active]:bg-foreground/5" :class="$store.docsViewMode.docsViewMode === 'grid' && 'active'" x-init @click="$store.docsViewMode.change('grid')" title="Grid view"><x-tabler-layout-grid class="size-5" stroke-width="1.5" /></button>
                </div>
            @endif
        </div>
    @endsection
@endif

@section('content')
    <div class="py-10">
        @if (blank($currfolder))
            <div class="mb-6 grid gap-3 md:grid-cols-4">
                <x-card class="p-4"><p class="text-2xs text-foreground/60">{{ __('Business Docs') }}</p><p class="mt-2 text-2xl font-semibold">{{ $proofSummary['business'] ?? 0 }}</p></x-card>
                <x-card class="p-4"><p class="text-2xs text-foreground/60">{{ __('Legal Docs') }}</p><p class="mt-2 text-2xl font-semibold">{{ $proofSummary['legal'] ?? 0 }}</p></x-card>
                <x-card class="p-4"><p class="text-2xs text-foreground/60">{{ __('Proof Assets') }}</p><p class="mt-2 text-2xl font-semibold">{{ $proofSummary['proof'] ?? 0 }}</p></x-card>
                <x-card class="p-4"><p class="text-2xs text-foreground/60">{{ __('Needs Review') }}</p><p class="mt-2 text-2xl font-semibold">{{ $proofSummary['needs_review'] ?? 0 }}</p></x-card>
            </div>

            <div class="mb-6 grid gap-4 xl:grid-cols-[minmax(0,1.35fr)_minmax(0,1fr)]">
                <x-card class="p-5">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <p class="text-2xs uppercase tracking-wide text-foreground/60">{{ __('Document Command Deck') }}</p>
                            <h3 class="mt-1 text-lg font-semibold text-heading-foreground">{{ __('Upgrade the original document area into an ops, legal, and proof cockpit') }}</h3>
                            <p class="mt-1 text-xs text-foreground/60">{{ __('Every action still lands on the core routes, but now you can triage review work, packet planning, and proof exports from one surface.') }}</p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <x-button variant="ghost-shadow" href="{{ route('dashboard.user.openai.documents.review') }}">{{ __('Review Board') }}</x-button>
                            <x-button variant="ghost-shadow" href="{{ route('dashboard.user.openai.documents.packets') }}">{{ __('Packet Planner') }}</x-button>
                            <x-button variant="ghost-shadow" href="{{ route('dashboard.user.openai.documents.all', ['filter' => 'proof']) }}">{{ __('Proof Queue') }}</x-button>
                        </div>
                    </div>

                    <div class="mt-5 grid gap-3 md:grid-cols-4">
                        <div class="rounded-2xl border border-black/5 bg-foreground/5 p-4"><p class="text-2xs text-foreground/60">{{ __('Ready') }}</p><p class="mt-2 text-2xl font-semibold">{{ $proofBoard['stats']['ready'] ?? 0 }}</p></div>
                        <div class="rounded-2xl border border-black/5 bg-foreground/5 p-4"><p class="text-2xs text-foreground/60">{{ __('Review') }}</p><p class="mt-2 text-2xl font-semibold">{{ $proofBoard['stats']['review'] ?? 0 }}</p></div>
                        <div class="rounded-2xl border border-black/5 bg-foreground/5 p-4"><p class="text-2xs text-foreground/60">{{ __('Legal') }}</p><p class="mt-2 text-2xl font-semibold">{{ $proofBoard['stats']['legal'] ?? 0 }}</p></div>
                        <div class="rounded-2xl border border-black/5 bg-foreground/5 p-4"><p class="text-2xs text-foreground/60">{{ __('Favorites') }}</p><p class="mt-2 text-2xl font-semibold">{{ $proofBoard['stats']['favorites'] ?? 0 }}</p></div>
                    </div>

                    <div class="mt-5 grid gap-3 md:grid-cols-3">
                        <div class="rounded-2xl border border-black/5 p-4">
                            <p class="text-2xs uppercase tracking-wide text-foreground/60">{{ __('Ready To Share') }}</p>
                            <p class="mt-2 text-sm text-foreground/70">{{ __('Fast lane for documents that can be exported or shared now.') }}</p>
                            <x-button class="mt-3" variant="link" href="{{ route('dashboard.user.openai.documents.all', ['filter' => 'proof']) }}">{{ __('Open lane') }}</x-button>
                        </div>
                        <div class="rounded-2xl border border-black/5 p-4">
                            <p class="text-2xs uppercase tracking-wide text-foreground/60">{{ __('Legal Packets') }}</p>
                            <p class="mt-2 text-sm text-foreground/70">{{ __('Surface contracts, incidents, notices, and compliance-heavy records.') }}</p>
                            <x-button class="mt-3" variant="link" href="{{ route('dashboard.user.openai.documents.all', ['filter' => 'legal']) }}">{{ __('Open lane') }}</x-button>
                        </div>
                        <div class="rounded-2xl border border-black/5 p-4">
                            <p class="text-2xs uppercase tracking-wide text-foreground/60">{{ __('Action Queue') }}</p>
                            <p class="mt-2 text-sm text-foreground/70">{{ __('Catch drafts, missing context, and unsigned material that still needs shaping.') }}</p>
                            <x-button class="mt-3" variant="link" href="{{ route('dashboard.user.openai.documents.all', ['filter' => 'needs_review']) }}">{{ __('Open lane') }}</x-button>
                        </div>
                    </div>
                </x-card>

                <div class="grid gap-4">
                    <x-card class="p-5">
                        <div class="mb-3 flex items-center justify-between gap-2">
                            <div>
                                <p class="text-2xs uppercase tracking-wide text-foreground/60">{{ __('Spotlight') }}</p>
                                <h4 class="text-sm font-semibold text-heading-foreground">{{ __('Highest priority document right now') }}</h4>
                            </div>
                            <x-button variant="link" href="{{ route('dashboard.user.openai.documents.review') }}">{{ __('Review') }}</x-button>
                        </div>
                        @if(($proofBoard['spotlight'] ?? null))
                            <a class="block rounded-2xl border border-black/5 p-4 transition hover:bg-foreground/5" href="{{ route('dashboard.user.openai.documents.single', $proofBoard['spotlight']->slug) }}">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-semibold text-heading-foreground">{{ $proofBoard['spotlight']->title ?: str($proofBoard['spotlight']->input)->limit(56) }}</p>
                                        <p class="mt-1 text-2xs text-foreground/60">{{ $proofBoard['spotlight']->proof_meta['summary'] ?? __('Core document') }}</p>
                                    </div>
                                    <span class="rounded-full bg-foreground/5 px-2.5 py-1 text-2xs">{{ $proofBoard['spotlight']->proof_meta['status_label'] ?? __('Open') }}</span>
                                </div>
                                <div class="mt-3 flex flex-wrap gap-2">
                                    @foreach(($proofBoard['spotlight']->proof_meta['tags'] ?? []) as $tag)
                                        <span class="rounded-full bg-foreground/5 px-2.5 py-1 text-2xs">{{ $tag }}</span>
                                    @endforeach
                                </div>
                            </a>
                        @else
                            <p class="text-xs text-foreground/60">{{ __('Nothing urgent right now.') }}</p>
                        @endif
                    </x-card>

                    <x-card class="p-5">
                        <div class="mb-3 flex items-center justify-between gap-2">
                            <h4 class="text-sm font-semibold text-heading-foreground">{{ __('Review lane') }}</h4>
                            <x-button variant="link" href="{{ route('dashboard.user.openai.documents.all', ['filter' => 'needs_review']) }}">{{ __('Open') }}</x-button>
                        </div>
                        <div class="space-y-3">
                            @forelse(($proofBoard['review'] ?? collect()) as $entry)
                                <a class="flex items-start justify-between gap-3 rounded-xl border border-black/5 p-3 transition hover:bg-foreground/5" href="{{ route('dashboard.user.openai.documents.single', $entry->slug) }}">
                                    <div>
                                        <p class="text-sm font-medium text-heading-foreground">{{ $entry->title ?: str($entry->input)->limit(52) }}</p>
                                        <p class="mt-1 text-2xs text-foreground/60">{{ $entry->proof_meta['action_label'] ?? __('Review') }}</p>
                                    </div>
                                    <span class="rounded-full bg-amber-500/10 px-2.5 py-1 text-2xs font-medium text-amber-600">{{ __('Review') }}</span>
                                </a>
                            @empty
                                <p class="text-xs text-foreground/60">{{ __('No review blockers right now.') }}</p>
                            @endforelse
                        </div>
                    </x-card>
                </div>
            </div>

            <div class="mb-6 grid gap-4 lg:grid-cols-3">
                @foreach (['legal' => __('Recent Legal'), 'proof' => __('Recent Proof'), 'ready' => __('Ready To Share')] as $bucketKey => $bucketLabel)
                    <x-card class="p-5">
                        <div class="mb-3 flex items-center justify-between gap-2">
                            <h4 class="text-sm font-semibold text-heading-foreground">{{ $bucketLabel }}</h4>
                            @php $bucketFilter = $bucketKey === 'ready' ? 'proof' : $bucketKey; @endphp
                            <x-button variant="link" href="{{ route('dashboard.user.openai.documents.all', ['filter' => $bucketFilter]) }}">{{ __('Browse') }}</x-button>
                        </div>
                        <div class="space-y-3">
                            @forelse(($proofBoard[$bucketKey] ?? collect()) as $entry)
                                <a class="block rounded-xl border border-black/5 p-3 transition hover:bg-foreground/5" href="{{ route('dashboard.user.openai.documents.single', $entry->slug) }}">
                                    <div class="flex items-center justify-between gap-3">
                                        <p class="text-sm font-medium text-heading-foreground">{{ $entry->title ?: str($entry->input)->limit(46) }}</p>
                                        <span class="rounded-full bg-foreground/5 px-2.5 py-1 text-2xs">{{ $entry->proof_meta['lane_label'] ?? __('Open') }}</span>
                                    </div>
                                    <p class="mt-1 text-2xs text-foreground/60">{{ $entry->proof_meta['summary'] ?? __('Core document') }}</p>
                                </a>
                            @empty
                                <p class="text-xs text-foreground/60">{{ __('Nothing in this lane yet.') }}</p>
                            @endforelse
                        </div>
                    </x-card>
                @endforeach
            </div>
        @endif

        @if ($currfolder == null)
            <div class="mb-6 flex flex-col justify-between gap-4 md:flex-row md:items-center">
                @if (isset(auth()->user()->folders) && count(auth()->user()->folders) > 0)
                    <div class="grid grow grid-cols-3 !gap-5 max-md:grid-cols-1">
                        @foreach (auth()->user()->folders ?? [] as $folder)
                            <x-documents.folder :$folder />
                        @endforeach
                    </div>
                @endif

                <x-dropdown.dropdown class="shrink-0 md:ms-auto" class:dropdown-dropdown="max-lg:end-auto max-lg:start-0" :teleport="false" offsetY="1rem" anchor="end">
                    <x-slot:trigger class="px-2 py-1" variant="link" size="xs">{{ __('Options') }}<x-tabler-dots class="size-5" /></x-slot:trigger>
                    <x-slot:dropdown class="p-1">
                        <form class="w-full" x-data="{ selectedAction: 'delete' }" @submit.prevent="if (selectedAction === 'delete') { $store.documentsSelection.bulkDelete('all', { confirmSelectedMessage: '{{ __('Are you sure you want to delete all documents?') }}', deleteUrl: '{{ route('dashboard.user.openai.documents.bulkDelete') }}' });}">
                            <x-button class="w-full rounded-md hover:bg-rose-500 hover:text-white" variant="none" type="submit">
                                <x-tabler-trash class="size-4" />
                                {{ __('Delete All') }}
                            </x-button>
                        </form>
                    </x-slot:dropdown>
                </x-dropdown.dropdown>
            </div>
        @else
            <div class="mb-6 flex items-center gap-3">
                <x-button class="aspect-square rounded-lg" href="{{ route('dashboard.user.openai.documents.all') }}" variant="secondary" title="{{ __('Back to documents') }}"><x-tabler-arrow-left /></x-button>
                <x-documents.folder :folder="$currfolder" folder-single-view="{{ true }}" />
            </div>
        @endif

        @if (!$items || count($items) === 0)
            @include('panel.user.openai.documents_empty')
        @else
            @include('panel.user.openai.documents_container')
        @endif
    </div>
@endsection
