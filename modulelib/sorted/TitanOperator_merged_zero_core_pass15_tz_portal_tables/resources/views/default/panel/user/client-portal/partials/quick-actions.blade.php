<x-card class="mb-0">
    <div class="grid grid-cols-1 gap-3 px-5 py-5 md:grid-cols-2 xl:grid-cols-4">
        <a href="{{ route('dashboard.user.client_portal.builder', ['template' => data_get($selectedPortalTemplate, 'slug')]) }}" class="rounded-2xl border p-4 hover:border-primary/30">
            <p class="mb-1 font-semibold">{{ __('Open builder') }}</p>
            <p class="mb-0 text-2xs text-heading-foreground/70">{{ __('Continue the existing chatbot build flow.') }}</p>
        </a>
        <a href="{{ route('dashboard.user.client_portal.preview', ['template' => data_get($selectedPortalTemplate, 'slug')]) }}" class="rounded-2xl border p-4 hover:border-primary/30">
            <p class="mb-1 font-semibold">{{ __('Preview runtime') }}</p>
            <p class="mb-0 text-2xs text-heading-foreground/70">{{ __('Open the live client-facing widget preview.') }}</p>
        </a>
        <a href="{{ route('dashboard.user.client_portal.inbox', ['template' => data_get($selectedPortalTemplate, 'slug')]) }}" class="rounded-2xl border p-4 hover:border-primary/30">
            <p class="mb-1 font-semibold">{{ __('Review inbox') }}</p>
            <p class="mb-0 text-2xs text-heading-foreground/70">{{ __('Check recent conversations and pinned chats.') }}</p>
        </a>
        <a href="{{ route('dashboard.user.client_portal.install', ['template' => data_get($selectedPortalTemplate, 'slug')]) }}" class="rounded-2xl border p-4 hover:border-primary/30">
            <p class="mb-1 font-semibold">{{ __('Install as app') }}</p>
            <p class="mb-0 text-2xs text-heading-foreground/70">{{ __('Use the manifest and service worker shell.') }}</p>
        </a>
    </div>
</x-card>
