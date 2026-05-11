<div class="mb-6 rounded-3xl border border-heading-foreground/10 bg-gradient-to-br from-heading-foreground/5 via-transparent to-heading-foreground/10 p-6">
    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="mb-1 text-xs uppercase tracking-[0.3em] text-heading-foreground/60">{{ __('Client Portal') }}</p>
            <h1 class="mb-2 text-2xl font-semibold">{{ __('Use the chatbot build as your client-facing portal') }}</h1>
            <p class="max-w-3xl text-sm text-heading-foreground/70">{{ __('Configure, customize, train, embed, and ship each portal as an installable runtime using the existing chatbot build flow.') }}</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <x-button href="{{ route('dashboard.user.client_portal.builder', ['template' => data_get($selectedPortalTemplate, 'slug')]) }}">{{ __('Open Builder') }}</x-button>
            <x-button variant="ghost-shadow" href="{{ route('dashboard.user.client_portal.history', ['template' => data_get($selectedPortalTemplate, 'slug')]) }}">{{ __('View History') }}</x-button>
            <x-button variant="ghost-shadow" href="{{ route('dashboard.user.client_portal.preview', ['template' => data_get($selectedPortalTemplate, 'slug')]) }}">{{ __('Preview Runtime') }}</x-button>
            <x-button variant="ghost-shadow" href="{{ route('dashboard.user.client_portal.install', ['template' => data_get($selectedPortalTemplate, 'slug')]) }}">{{ __('Install Guide') }}</x-button>
        </div>
    </div>
</div>
