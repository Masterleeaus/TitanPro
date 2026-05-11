<div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
    @foreach ($portalTemplates as $template)
        <x-card class="mb-0">
            <div class="px-5 py-5">
                <div class="mb-3 flex items-center justify-between gap-3">
                    <h3 class="mb-0 font-heading text-lg font-semibold">{{ data_get($template, 'title') }}</h3>
                    <span class="rounded-full border px-3 py-1 text-2xs">{{ data_get($template, 'accent') }}</span>
                </div>
                <p class="mb-4 text-2xs text-heading-foreground/70">{{ data_get($template, 'summary') }}</p>
                <div class="mb-3 flex flex-wrap gap-2">
                    @foreach (data_get($template, 'channels', []) as $channel)
                        <span class="rounded-full border px-3 py-1 text-2xs">{{ ucfirst($channel) }}</span>
                    @endforeach
                </div>
                <div class="mb-4 flex flex-wrap gap-2">
                    @foreach (data_get($template, 'training', []) as $source)
                        <span class="rounded-full bg-heading-foreground/5 px-3 py-1 text-2xs">{{ strtoupper($source) }}</span>
                    @endforeach
                </div>
                <div class="flex flex-wrap gap-3">
                    <x-button href="{{ route('dashboard.user.client_portal.builder', ['template' => data_get($template, 'slug')]) }}" variant="outline">{{ __('Use template') }}</x-button>
                    <x-button href="{{ route('dashboard.user.client_portal.preview', ['template' => data_get($template, 'slug')]) }}" variant="ghost-shadow">{{ __('Preview') }}</x-button>
                </div>
            </div>
        </x-card>
    @endforeach
</div>
