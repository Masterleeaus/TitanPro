@extends('panel.layout.app', ['disable_tblr' => true])
@section('title', __('Workbook'))
@section('titlebar_pretitle', __('Edit your generations.'))
@php
    $title = $workbook->generator->type === 'image' ? str()->limit($workbook->input, 40) : $workbook->title;
@endphp
@section('titlebar_title', $title)
@section('titlebar_actions')
    {{-- Edit with AI Editor --}}
    @if ($setting->feature_ai_advanced_editor && $workbook->generator->type !== 'voiceover' && $workbook->generator->type !== \App\Domains\Entity\Enums\EntityEnum::ISOLATOR->value)
        <x-button
            variant="ghost-shadows"
            href="{{ route('dashboard.user.generator.index', $workbook->slug) }}"
        >
            @lang('Open with AI Editor')
        </x-button>
    @endif
@endsection
@section('titlebar_actions_after')
    <div class="flex items-center gap-3 max-lg:hidden lg:ms-4">
        <x-button
            variant="ghost-shadow"
            href="{{ route('dashboard.user.openai.documents.review') }}"
        >
            {{ __('Review Board') }}
        </x-button>
        <x-button
            variant="ghost-shadow"
            href="{{ route('dashboard.user.openai.documents.proof.export', $workbook->slug) }}"
        >
            {{ __('Export Proof') }}
        </x-button>
        <x-dropdown.dropdown
            class="doc-share-dropdown"
            class:dropdown-dropdown="max-lg:end-auto max-lg:start-0"
            anchor="end"
            offsetY="20px"
        >
            <x-slot:trigger>
                {{ __('Share') }}
                <span
                    class="inline-grid size-6 shrink-0 place-items-center rounded-md bg-foreground/10 transition-all group-hover/dropdown:scale-105 group-hover/dropdown:bg-heading-foreground group-hover/dropdown:text-heading-background"
                >
                    <x-tabler-share class="size-4" />
                </span>
            </x-slot:trigger>
            <x-slot:dropdown
                class="py-1 text-2xs"
            >
                <x-button
                    class="w-full justify-start rounded-none px-3 py-2 text-start hover:bg-heading-foreground/5"
                    variant="link"
                    target="_blank"
                    href="http://twitter.com/share?text={{ $workbook->output }}"
                >
                    <x-tabler-brand-x />
                    @lang('X')
                </x-button>
                <x-button
                    class="w-full justify-start rounded-none px-3 py-2 text-start hover:bg-heading-foreground/5"
                    variant="link"
                    target="_blank"
                    href="https://wa.me/?text={{ htmlspecialchars($workbook->output) }}"
                >
                    <x-tabler-brand-whatsapp />
                    @lang('Whatsapp')
                </x-button>
                <x-button
                    class="w-full justify-start rounded-none px-3 py-2 text-start hover:bg-heading-foreground/5"
                    variant="link"
                    target="_blank"
                    href="https://t.me/share/url?url={{ request()->host() }}&text={{ htmlspecialchars($workbook->output) }}"
                >
                    <x-tabler-brand-telegram />
                    @lang('Telegram')
                </x-button>
            </x-slot:dropdown>
        </x-dropdown.dropdown>

        @if (!empty($integrations) && $checkIntegration && $wordpressExist)
            <x-dropdown.dropdown
                class="doc-integrate-publish-dropdown"
                class:dropdown-dropdown="max-lg:end-auto max-lg:start-0"
                anchor="end"
                offsetY="20px"
            >
                <x-slot:trigger
                    variant="success"
                >
                    {{ __('Publish') }}
                </x-slot:trigger>
                <x-slot:dropdown
                    class="min-w-48 text-xs"
                >
                    <p class="border-b px-3 py-3 text-foreground/70">
                        @lang('Integrations')
                    </p>
                    <div class="pb-2">
                        @foreach ($integrations as $integration)
                            <x-button
                                class="w-full justify-start rounded-none px-3 py-2 text-start hover:bg-heading-foreground/5"
                                variant="link"
                                href="{{ route('dashboard.user.integration.share.workbook', [$integration->id, $workbook->id]) }}"
                            >
                                {{ $integration?->integration?->app }}
                            </x-button>
                        @endforeach

                    </div>
                </x-slot:dropdown>
            </x-dropdown.dropdown>
        @endif
    </div>
@endsection


@section('content')
    <div class="py-10">
        <div class="mx-auto grid w-full gap-6 lg:w-4/5 lg:grid-cols-[minmax(0,1fr)_360px]">
            <div>
                @include('panel.user.openai.documents_workbook_textarea')
            </div>

            <aside class="space-y-4">
                <x-card class="p-5">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-2xs uppercase tracking-wide text-foreground/60">{{ __('Proof Command Deck') }}</p>
                            <h3 class="mt-1 text-lg font-semibold text-heading-foreground">{{ $proofMeta['status_label'] ?? __('In Progress') }}</h3>
                            <p class="mt-1 text-2xs text-foreground/60">{{ $proofMeta['summary'] ?? __('Core document record') }}</p>
                        </div>
                        <span class="inline-flex rounded-full px-2.5 py-1 text-2xs font-medium bg-foreground/5 text-heading-foreground">{{ $proofMeta['lane_label'] ?? ucfirst($proofMeta['bucket'] ?? 'business') }}</span>
                    </div>

                    <div class="mt-4">
                        <div class="mb-2 flex items-center justify-between gap-3 text-2xs text-foreground/60">
                            <span>{{ __('Readiness score') }}</span>
                            <strong class="text-heading-foreground">{{ $proofScore }}%</strong>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-foreground/10">
                            <div class="h-full rounded-full bg-accent" style="width: {{ min(100, max(5, $proofScore)) }}%"></div>
                        </div>
                    </div>

                    <div class="mt-4 flex flex-wrap gap-2">
                        <x-button variant="ghost-shadow" href="{{ route('dashboard.user.openai.documents.proof.export', $workbook->slug) }}">{{ __('Export Proof') }}</x-button>
                        <x-button variant="ghost-shadow" href="{{ route('dashboard.user.openai.documents.review', ['lane' => $proofMeta['lane'] ?? 'all']) }}">{{ __('Open lane') }}</x-button>
                        <x-button variant="ghost-shadow" href="{{ route('dashboard.user.openai.documents.packets') }}">{{ __('Packet Planner') }}</x-button>
                    </div>
                </x-card>

                <x-card class="p-5">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-2xs uppercase tracking-wide text-foreground/60">{{ __('Proof Checklist') }}</p>
                            <h4 class="mt-1 text-sm font-semibold text-heading-foreground">{{ __('What still needs attention?') }}</h4>
                        </div>
                        <span class="rounded-full bg-foreground/5 px-3 py-1 text-2xs font-medium text-heading-foreground">{{ collect($proofChecklist ?? [])->where('ok', true)->count() }}/{{ count($proofChecklist ?? []) }}</span>
                    </div>
                    <ul class="mt-3 space-y-2 text-2xs text-foreground/70">
                        @foreach(($proofChecklist ?? []) as $item)
                            <li class="flex items-center justify-between gap-3 rounded-xl border border-black/5 p-3">
                                <span>{{ $item['label'] }}</span>
                                <strong class="{{ ($item['ok'] ?? false) ? 'text-emerald-600' : 'text-amber-600' }}">{{ $item['value'] }}</strong>
                            </li>
                        @endforeach
                    </ul>
                </x-card>

                <x-card class="p-5">
                    <p class="text-2xs uppercase tracking-wide text-foreground/60">{{ __('Packet Assembly') }}</p>
                    <div class="mt-3 space-y-3">
                        @foreach(($packetDeck ?? []) as $item)
                            <div class="flex items-center justify-between gap-3 rounded-xl border border-black/5 p-3">
                                <span class="text-2xs text-foreground/60">{{ $item['label'] }}</span>
                                <strong class="text-right text-2xs text-heading-foreground">{{ $item['value'] }}</strong>
                            </div>
                        @endforeach
                    </div>
                </x-card>

                <x-card class="p-5">
                    <p class="text-2xs uppercase tracking-wide text-foreground/60">{{ __('Proof Timeline') }}</p>
                    <div class="mt-3 space-y-3">
                        @foreach(($proofTimeline ?? []) as $item)
                            <div class="flex items-start justify-between gap-3 rounded-xl border border-black/5 p-3">
                                <span class="text-2xs text-foreground/60">{{ $item['label'] }}</span>
                                <strong class="text-right text-2xs text-heading-foreground">{{ $item['value'] }}</strong>
                            </div>
                        @endforeach
                    </div>
                </x-card>

                <x-card class="p-5">
                    <p class="text-2xs uppercase tracking-wide text-foreground/60">{{ __('Suggested next move') }}</p>
                    <div class="mt-3 space-y-2 text-2xs text-foreground/70">
                        <p>{{ $proofMeta['action_label'] ?? __('Review and export') }}</p>
                        <div class="flex flex-wrap gap-2 pt-2">
                            <x-button variant="ghost-shadow" href="{{ route('dashboard.user.openai.documents.all', ['filter' => 'proof']) }}">{{ __('Proof queue') }}</x-button>
                            <x-button variant="ghost-shadow" href="{{ route('dashboard.user.openai.documents.all', ['filter' => 'legal']) }}">{{ __('Legal docs') }}</x-button>
                        </div>
                    </div>
                </x-card>
            </aside>
        </div>
    </div>
@endsection

@php
    $lang_with_flags = [];
    foreach (LaravelLocalization::getSupportedLocales() as $lang => $properties) {
        $lang_with_flags[] = [
            'lang' => $lang,
            'name' => $properties['native'],
            'flag' => country2flag(substr($properties['regional'], strrpos($properties['regional'], '_') + 1)),
        ];
    }
@endphp
@push('script')
    <link
        rel="stylesheet"
        href="{{ custom_theme_url('/assets/libs/katex/katex.min.css') }}"
    >

    <script>
        const lang_with_flags = @json($lang_with_flags);
    </script>
    <script src="{{ custom_theme_url('/assets/libs/beautify-html.min.js') }}"></script>
    <script src="{{ custom_theme_url('/assets/libs/ace/src-min-noconflict/ace.js') }}"></script>
    <script src="{{ custom_theme_url('/assets/libs/ace/src-min-noconflict/ext-language_tools.js') }}"></script>
    <script src="{{ custom_theme_url('/assets/libs/markdownit/markdown-it.min.js') }}"></script>
    <script src="{{ custom_theme_url('/assets/libs/turndown.js') }}"></script>
    <script src="{{ custom_theme_url('/assets/libs/katex/katex.min.js') }}"></script>
    <script src="{{ custom_theme_url('/assets/libs/vscode-markdown-it-katex/index.js') }}"></script>
    <script src="{{ custom_theme_url('/assets/libs/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ custom_theme_url('/assets/js/panel/tinymce-theme-handler.js') }}"></script>
    <script src="{{ custom_theme_url('/assets/js/panel/workbook.js') }}"></script>

    @if ($openai->type === 'voiceover' || $openai->type === \App\Domains\Entity\Enums\EntityEnum::ISOLATOR->value)
        <script src="{{ custom_theme_url('/assets/libs/wavesurfer/wavesurfer.js') }}"></script>
        <script src="{{ custom_theme_url('/assets/js/panel/voiceover.js') }}"></script>
    @endif

    @if ($openai->type == 'code')
        <link
            rel="stylesheet"
            href="{{ custom_theme_url('/assets/libs/prism/prism.css') }}"
        />
        <script src="{{ custom_theme_url('/assets/libs/prism/prism.js') }}"></script>
        <script src="{{ custom_theme_url('/assets/js/format-string.js') }}"></script>

        <script>
            window.Prism = window.Prism || {};
            window.Prism.manual = true;
            document.addEventListener('DOMContentLoaded', (event) => {
                "use strict";

                const codeLang = document.querySelector('#code_lang');
                const codePre = document.querySelector('#code-pre');
                const codeOutput = codePre?.querySelector('#code-output');

                if (codeOutput) {
                    // saving for copy
                    window.codeRaw = codeOutput.innerText;

                    codeOutput.innerHTML = lqdFormatString(codeOutput.textContent);
                };
            });
        </script>
    @endif
@endpush
